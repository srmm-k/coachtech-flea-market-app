<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CustomLoginController;
use App\Http\Controllers\CustomRegisterController;
use App\Http\Controllers\StripePaymentController;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//トップページ（おすすめ・マイリスト・検索）すべて統合
Route::get('/', [SearchController::class, 'index'])->name('top');

//出品一覧・詳細
Route::get('/listing', [ListingController::class, 'index'])->name('listing');
Route::get('/item/{item_id}', [ListingController::class, 'show'])->name('item.show');

//検索
Route::get('/search', [SearchController::class, 'index'])->name('search');

//履歴の削除
Route::post('/search/history/delete', [SearchController::class, 'deleteHistory'])->name('search.history.delete');

//認証関連
Route::get('/register', [CustomRegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [CustomRegisterController::class, 'register']);

Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomLoginController::class, 'login']);

// 認証画面
Route::get('/verify-email-info', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verify.email.info');


// メールリンクからのアクセス
Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();
    Auth::login($request->user());
    session()->regenerate();
    return redirect()->route('profile.show');
})->middleware(['web', 'signed'])->name('verification.verify');

// 再送メール
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');




//認証が必要な機能
Route::middleware(['auth'])->group(function() {
    //出品
    Route::get('/sell', [ListingController::class, 'create'])->name('sell');
    Route::post('/sell', [ListingController::class, 'store'])->name('sell.store');

    //マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    //プロフィール
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');


    //コメント
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

    //購入
    Route::get('/purchase/address/{item_id}', [ProfileController::class, 'editAddress'])->name('address.edit');
    Route::put('/purchase/address/{item_id}', [ProfileController::class, 'updateAddress'])->name('address.update');
    Route::get('/purchase/{id}', [PurchaseController::class, 'start'])->name('purchase.start');
    // Route::post('/purchase/{id}/complete', [PurchaseController::class, 'store'])->name('purchase.store');

    //いいね機能
    Route::post('/like/{listing}', [LikeController::class, 'like'])->name('like');
    Route::delete('/like/{listing}', [LikeController::class, 'unlike'])->name('unlike');

    //決済関連
    //購入処理の実行
    Route::post('/purchase/{id}/complete', [StripePaymentController::class, 'process'])->name('purchase.store');
    //決済成功・キャンセル後のリダイレクト先
    Route::get('/purchase/success', [StripePaymentController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel', [StripePaymentController::class, 'cancel'])->name('purchase.cancel');

    });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'show'])->name('profile.show');
    });
