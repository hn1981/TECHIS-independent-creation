<?php

namespace App\Http\Controllers;

use App\Models\Ramen;
use App\Models\Shop;
use App\Models\Review;
use App\Models\RamenImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RamenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //一覧画面表示
    public function index(Request $request)
    {
        // 検索実行時のバリデーション
        $this->validate($request, [
            'search' => 'nullable|string|max:255',
        ]);

        // ページのセッション情報
        session(['current_page' => $request->get('page', 1)]);

        $query = Ramen::query();

        // 検索キーワードがある場合に適用
        if ($request->has('search')) {
            $keyword = $request->input('search');
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhereHas('shop', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->orwhereHas('shop.prefecture', function ($query) use ($keyword) {
                    $query->where('prefecture_name', 'like', '%' . $keyword . '%');
                });
            });
        }

        // クエリ実行（検索キーワードがあればその結果、なければ商品すべてが表示）
        $ramens = $query->with('shop.prefecture')->orderBy('created_at', 'asc')->paginate();

        // 検索フォームの入力があった場合、ページネーションへ検索ワードを付帯した状態で戻す
        if ($request->has('search')) {
            $ramens->appends(['search' => $keyword]);
        }

        return view('ramen.index', compact('ramens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Ramen $ramen, Shop $shop)
    {
        $user = Auth::user();
        Log::info('Authenticated user:', ['user' => $user]);

        // 実行権限チェック
        // $this->authorize('store', $ramen);

        $shops = Shop::all();

        return view('ramen.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Ramen $ramen)
    {
        // 実行権限チェック
        // $this->authorize('store', $ramen);

        $this->validate($request, [
            // ramensテーブル
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|max:50',
            'description' => 'max:100',
            // reviewテーブル
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'max:500',
            // ramen_imagesテーブル
            'images' => 'array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 例：2MBまでの画像ファイル
        ]);

        DB::beginTransaction();

        try {
            // ラーメンデータの保存
            $ramen = $request->user()->ramens()->create([
                'shop_id' => $request->shop_id,
                'name' => $request->name,
                'description' => $request->description,
                'eating_date' => $request->eating_date,
            ]);
            // レビューデータの保存
            $review = $ramen->reviews()->create([
                'user_id' => $request->user()->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            // 画像ファイルがあれば保存
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('ramen_images', 'public');
                    $ramen->ramenImages()->create([
                        // 'user_id' => $request->user()->id,
                        'image_path' => $path,
                    ]);
                }
            }
        // 処理が成功したらコミット
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return back()->withErrors('エラーが発生しました。')->withInput();
        }

        return redirect('/ramens');
    }


    /**
     * Display the specified resource.
     */
    public function show(Ramen $ramen)
    {
        // 実行権限チェック
        // $this->authorize('update', $ramen);

        $shops = Shop::all();
        $reviews = $ramen->reviews()->get();
        $ramenImages = $ramen->ramenImages()->get();

        return view('/ramen.show', compact('ramen','shops', 'reviews', 'ramenImages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // 情報編集画面表示
    public function edit(Ramen $ramen)
    {
        // 実行権限チェック
        // $this->authorize('update', $ramen);

        $shops = Shop::all();
        $reviews = $ramen->reviews()->get();
        $ramenImages = $ramen->ramenImages()->get();

        return view('/ramen.edit', compact('ramen','shops', 'reviews', 'ramenImages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ramen $ramen)
    {
        //
        // 実行権限チェック
        // $this->authorize('update', $ramen);

        // 既存の画像の数を取得
        $existingImagesCount = $ramen->ramenImages()->count();

        // 新しくアップロードされる画像の数
        $newImagesCount = $request->hasFile('images') ? count($request->file('images')) : 0;

        // 合計の画像数
        $totalImagesCount = $existingImagesCount + $newImagesCount;

        // ユーザーが選択した削除する画像の数
        $deletingImagesCount = count($request->input('delete_images', []));

        // 実際の画像数（削除後）
        $actualImagesCount = $totalImagesCount - $deletingImagesCount;

        $this->validate($request, [
            // ramensテーブル
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|max:50',
            'description' => 'max:100',
            // reviewテーブル
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'max:500',
            // ramen_imagesテーブル
            'images' => [
                'array',
                'max:3',
                function ($attribute, $value, $fail) use ($actualImagesCount) {
                    if ($actualImagesCount > 3) {
                        $fail('画像は合計で3枚までにしてください。');
                    }
                },
            ],
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 例：2MBまでの画像ファイル
        ]);

        DB::beginTransaction();

        try {
            // ラーメンデータの保存
            $ramen->update([
                'shop_id' => $request->shop_id,
                'name' => $request->name,
                'description' => $request->description,
                'eating_date' => $request->eating_date,
            ]);

            // レビューデータの保存
            $userReview = $ramen->reviews()->where('user_id', $request->user()->id)->first();
            if ($userReview) {
                $userReview->update([
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);
            }

            // 画像ファイルがあれば保存
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('ramen_images', 'public');
                    $ramen->ramenImages()->create([
                        'user_id' => $request->user()->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // ユーザーがチェックした画像IDを取得
            $deleteImagesIds = $request->input('delete_images', []);

            // 取得した画像IDに対応する画像を削除
            foreach ($deleteImagesIds as $imageId) {
                $image = RamenImage::findOrFail($imageId);
                // ファイルシステムから画像を削除
                Storage::delete('public/' . $image->image_path);
                // データベースから画像レコードを削除
                $image->delete();
            }

        // 処理が成功したらコミット
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return back()->withErrors('エラーが発生しました。')->withInput();
        }

        // セッション情報取得
        $page = session('current_page', 1);

        return redirect('/ramens?page=' .$page);
    }

    /**
     * Remove the specified resource from storage.
     */
    // 商品情報削除
    public function destroy (Ramen $ramen)
    {
        // 実行権限チェック
        // $this->authorize('destroy', $item);

        // セッション情報取得
        $page = session('current_page', 1);

        // 削除実行
        $ramen->delete();

        // 削除後のアイテムの総数を取得
        $totalItems = Ramen::count();

        // ページネーションの表示数を取得
        $perPage = $ramen->getPerPageValue();

        // 最後のページ番号取得
        $lastPage = ceil($totalItems / $perPage);

        // ページ番号が取得したページ番号よりも大きい場合、取得したページ番号にリダイレクト
        if ($page > $lastPage) {
            return redirect('/ramens?page=' .$lastPage);
        }

        return redirect('/ramens?page=' .$page);
    }
}
