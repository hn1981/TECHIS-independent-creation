<?php

namespace App\Http\Controllers;

use App\Models\Ramen;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Review;
use App\Models\RamenImage;
use App\Models\ShopImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        session()->forget('adminSession');

        // 検索実行時のバリデーション
        $this->validate($request, [
            'search' => 'nullable|string|max:255',
        ]);

        // ページのセッション情報
        session(['current_page' => $request->get('page', 1)]);

        $query = Shop::query();

        // 検索キーワードがある場合に適用
        if ($request->has('search')) {
            $keyword = $request->input('search');
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('address', 'like', '%' . $keyword . '%')
                ->orWhereHas('prefecture', function ($query) use ($keyword) {
                    $query->where('prefecture_name', 'like', '%' . $keyword . '%');
                });
            });
        }

        $user = $request->user();

        // roleが0の時、$queryに条件を追加
        if ($user->role === 0) {
            $query = $query->where('user_id', $user->id);
        }

        // クエリ実行（検索キーワードがあればその結果、なければ商品すべてが表示）
        $shops = $query->with('prefecture')->where('user_id', $user->id)->orderBy('updated_at', 'desc')->paginate();

        // 検索フォームの入力があった場合、ページネーションへ検索ワードを付帯した状態で戻す
        if ($request->has('search')) {
            $shops->appends(['search' => $keyword]);
        }

        return view('shop.index', compact('shops'));
    }

    public function adminIndex(Request $request)
    {
        $user = $request->user();

        // 実行権限チェック
        $this->authorize('viewAny', $user);

        // 検索実行時のバリデーション
        $this->validate($request, [
            'search' => 'nullable|string|max:255',
        ]);

        // ページのセッション情報
        session(['current_page' => $request->get('page', 1)]);
        // 管理者のセッション情報登録
        session(['adminSession' => true]);

        $query = Shop::query();

        $user = $request->user();

        // 検索キーワードがある場合に適用
        if ($request->has('search')) {
            $keyword = $request->input('search');
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('address', 'like', '%' . $keyword . '%')
                ->orWhere('shops.id', 'like', '%' . $keyword . '%')
                ->orWhereHas('prefecture', function ($query) use ($keyword) {
                    $query->where('prefecture_name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('user', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            });
        }

        // クエリ実行（検索キーワードがあればその結果、なければ商品すべてが表示）
        $shops = $query->with('prefecture', 'user')->orderBy('updated_at', 'desc')->paginate();

        // 検索フォームの入力があった場合、ページネーションへ検索ワードを付帯した状態で戻す
        if ($request->has('search')) {
            $shops->appends(['search' => $keyword]);
        }

        return view('shop.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 実行権限チェック
        // $this->authorize('store', $ramen);

        // セッション情報（createメソッド実行直前のURL。セッション登録がない場合）を追加
        if (!session()->has('originalPreviousUrl')) {
            session(['originalPreviousUrl' => url()->previous()]);
        }

        $prefectures = Prefecture::all();

        return view('shop.create', compact('prefectures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 実行権限チェック
        // $this->authorize('store', $ramen);

        $this->validate($request, [
            // shopsテーブル
            'prefecture_id' => 'required|exists:prefectures,id',
            'name' => 'required|max:50',
            'address' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'description' => 'max:500',
            // shop_imagesテーブル
            'images' => 'array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 例：2MBまでの画像ファイル
        ]);

        DB::beginTransaction();

        try {
            // 店舗データの保存
            $shop = $request->user()->shops()->create([
                'prefecture_id' => $request->prefecture_id,
                'name' => $request->name,
                'address' => $request->address,
                'url' => $request->url,
                'description' => $request->description,
            ]);
            // 画像ファイルがあれば保存
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $image_data = base64_encode(file_get_contents($image));
                    $mime_type = $image->getMimeType();
                    $shop->shopImages()->create([
                        // 'user_id' => $request->user()->id,
                        'image' => $image_data,
                        'mime_type' => $mime_type,
                    ]);
                }
            }

            /*
            // 画像ファイルがあれば保存(パスの場合)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('shop_images', 'public');
                    $shop->shopImages()->create([
                        // 'user_id' => $request->user()->id,
                        'image_path' => $path,
                    ]);
                }
            }
            */

        // 処理が成功したらコミット
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return back()->withErrors('エラーが発生しました。')->withInput();
        }

        // createメソッド取得したoriginalPreviousUrlセッションを変数に代入
        $originalPreviousUrl = session('originalPreviousUrl', '/shops');

        // セッション削除
        session()->forget('originalPreviousUrl');

        return redirect($originalPreviousUrl);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        // 実行権限チェック
        // $this->authorize('update', $shop);

        $shopImages = $shop->shopImages()->get();

        return view('/shop.show', compact('shop', 'shopImages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        // 実行権限チェック
        // $this->authorize('update', $ramen);

        $prefectures = Prefecture::all();
        $shopImages = $shop->shopImages()->get();

        return view('/shop.edit', compact('shop', 'prefectures', 'shopImages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        //
        // 実行権限チェック
        // $this->authorize('update', $shop);

        // 既存の画像の数を取得
        $existingImagesCount = $shop->shopImages()->count();

        // 新しくアップロードされる画像の数
        $newImagesCount = $request->hasFile('images') ? count($request->file('images')) : 0;

        // 合計の画像数
        $totalImagesCount = $existingImagesCount + $newImagesCount;

        // ユーザーが選択した削除する画像の数
        $deletingImagesCount = count($request->input('delete_images', []));

        // 実際の画像数（削除後）
        $actualImagesCount = $totalImagesCount - $deletingImagesCount;

        $this->validate($request, [
            // shopsテーブル
            'prefecture_id' => 'required|exists:prefectures,id',
            'name' => 'required|max:50',
            'address' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'description' => 'max:500',
            // shop_imagesテーブル
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
            // 店舗データの保存
            $shop->update([
                'shop_id' => $request->shop_id,
                'name' => $request->name,
                'address' => $request->address,
                'url' => $request->url,
                'description' => $request->description,
            ]);

            // 画像ファイルがあれば保存
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $image_data = base64_encode(file_get_contents($image));
                    $mime_type = $image->getMimeType();
                    $shop->shopImages()->create([
                        // 'user_id' => $request->user()->id,
                        'image' => $image_data,
                        'mime_type' => $mime_type,
                    ]);
                }
            }

            /*
            // 画像ファイルがあれば保存（パスの場合）
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('shop_images', 'public');
                    $shop->shopImages()->create([
                        // 'user_id' => $request->user()->id,
                        'image_path' => $path,
                    ]);
                }
            }
            */

            // ユーザーがチェックした画像IDを取得
            $deleteImagesIds = $request->input('delete_images', []);

            // 取得した画像IDに対応する画像を削除
            foreach ($deleteImagesIds as $imageId) {
                $image = ShopImage::findOrFail($imageId);
                // ファイルシステムから画像を削除
                // Storage::delete('public/' . $image->image_path);
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

        $adminSession = session('adminSession');

        session()->forget('adminSession');

        if ($adminSession) {
            return redirect()->route('shops.adminIndex', ['page' => $page]);
        } else {
            return redirect()->route('shops.index', ['page' => $page]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // 商品情報削除
    public function destroy (Shop $shop)
    {
        // 実行権限チェック
        // $this->authorize('destroy', $shop);

        // セッション情報取得
        $page = session('current_page', 1);

        // 削除実行
        $shop->delete();

        // 削除後のアイテムの総数を取得
        if (session('adminSession')) {
            $totalItems = Shop::count();
        } else {
            $userId = Auth::id();
            $totalItems = Shop::where('user_id', $userId)->count();
        }

        // ページネーションの表示数を取得
        $perPage = $shop->getPerPageValue();

        // 最後のページ番号取得
        $lastPage = ceil($totalItems / $perPage);

        // ページ番号が取得したページ番号よりも大きい場合、取得したページ番号にリダイレクト
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $adminSession = session('adminSession');

        session()->forget('adminSession');

        if ($adminSession) {
            return redirect()->route('shops.adminIndex', ['page' => $page]);
        } else {
            return redirect()->route('shops.index', ['page' => $page]);
        }
    }
}
