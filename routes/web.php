<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

route::middleware(['auth'])->group(function ()
{
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // ユーザーグループ
    Route::prefix('users')->group(function () {
        // 情報一覧
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        // 編集画面・実行
        Route::get('/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::get('/edit/{user}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.adminEdit');
        Route::put('/update/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        // 削除
        Route::delete('/destroy/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    });

    // ラーメングループ
    Route::prefix('ramens')->group(function () {
        // 情報一覧
        Route::get('/', [App\Http\Controllers\RamenController::class, 'index'])->name('ramens.index');
        // 情報一覧（管理者）
        Route::get('/admin', [App\Http\Controllers\RamenController::class, 'adminIndex'])->name('ramens.adminIndex');
        // 詳細画面
        Route::get('/show/{ramen}', [App\Http\Controllers\RamenController::class, 'show'])->name('ramens.show');
        // 登録画面・実行
        Route::get('/create', [App\Http\Controllers\RamenController::class, 'create'])->name('ramens.create');
        Route::post('/store', [App\Http\Controllers\RamenController::class, 'store'])->name('ramens.store');
        // 編集画面・実行
        Route::get('/edit/{ramen}', [App\Http\Controllers\RamenController::class, 'edit'])->name('ramens.edit');
        Route::put('/update/{ramen}', [\App\Http\Controllers\RamenController::class, 'update'])->name('ramens.update');
        // 削除
        Route::delete('/destroy/{ramen}', [\App\Http\Controllers\RamenController::class, 'destroy'])->name('ramens.destroy');
    });

    // 店舗グループ
    Route::prefix('shops')->group(function () {
        // 情報一覧
        Route::get('/', [App\Http\Controllers\ShopController::class, 'index'])->name('shops.index');
        // 情報一覧（管理者）
        Route::get('/admin', [App\Http\Controllers\ShopController::class, 'adminIndex'])->name('shops.adminIndex');
                // 詳細画面
        Route::get('/show/{shop}', [App\Http\Controllers\ShopController::class, 'show'])->name('shops.show');
        // // 登録画面・実行
        Route::get('/create', [App\Http\Controllers\ShopController::class, 'create'])->name('shops.create');
        Route::post('/store', [App\Http\Controllers\ShopController::class, 'store'])->name('shops.store');
        // 編集画面・実行
        Route::get('/edit/{shop}', [App\Http\Controllers\ShopController::class, 'edit'])->name('shops.edit');
        Route::put('/update/{shop}', [\App\Http\Controllers\ShopController::class, 'update'])->name('shops.update');
        // 削除
        Route::delete('/destroy/{shop}', [\App\Http\Controllers\ShopController::class, 'destroy'])->name('shops.destroy');
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/create', [App\Http\Controllers\ReviewController::class, 'crate'])->name('reviews.create');
        Route::post('/store', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    });

    Route::prefix('items')->group(function () {
        Route::get('/', [App\Http\Controllers\ItemController::class, 'index']);
        Route::get('/add', [App\Http\Controllers\ItemController::class, 'add']);
        Route::post('/add', [App\Http\Controllers\ItemController::class, 'add']);
    });
});