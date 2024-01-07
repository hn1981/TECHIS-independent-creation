<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //一覧画面表示
    public function index(Request $request)
    {
        $user = $request->user();

        // 実行権限チェック
        $this->authorize('viewAny', $user);

        session(['adminSession' => true]);

        // 検索実行時のバリデーション
        $this->validate($request, [
            'search' => 'nullable|string|max:255',
        ]);

        // ページのセッション情報
        session(['current_page' => $request->get('page', 1)]);
        // 管理者のセッション情報登録
        session(['adminSession' => true]);

        $query = User::query();

        // 検索キーワードがある場合に適用
        if ($request->has('search')) {
            $keyword = $request->input('search');
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

            // クエリ実行（検索キーワードがあればその結果、なければ商品すべてが表示）
        $users = $query->orderBy('created_at', 'asc')->paginate();

        // 検索フォームの入力があった場合、ページネーションへ検索ワードを付帯した状態で戻す
        if ($request->has('search')) {
            $users->appends(['search' => $keyword]);
        }

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user = null)
    {

        if ($user === null) {
            session()->forget('adminSession');
            $user = Auth()->user();
        }
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        // 実行権限チェック
        // $this->authorize('update', $ramen);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email:filter|unique:users,email,' . $user->id . ',id', // . $user->id . はユニーク検索から除外するid ',id' はidが保存されているカラムの名前
            'password' => 'nullable|max:255|confirmed|string|min:8',
        ]);

        // ユーザーデータの保存
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update([
                'password' => $request->password,
            ]);
        }

        $page = session('current_page');
        $adminSession = session('adminSession');

        session()->forget('adminSession');

        if ($adminSession) {
            return redirect()->route('users.index', ['page' => $page]);
        } else {
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // 商品情報削除
    public function destroy (User $user)
    {
        // 実行権限チェック
        // $this->authorize('destroy', $shop);

        // セッション情報取得
        $page = session('current_page', 1);

        // 削除実行
        $user->delete();

        // 削除後のアイテムの総数を取得
        $totalItems = User::count();

        // ページネーションの表示数を取得
        $perPage = $user->getPerPageValue();

        // 最後のページ番号取得
        $lastPage = ceil($totalItems / $perPage);

        // ページ番号が取得したページ番号よりも大きい場合、取得したページ番号にリダイレクト
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        session()->forget('adminSession');

        return redirect()->route('users.index', ['page' => $page]);
    }
}
