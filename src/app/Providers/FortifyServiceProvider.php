<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Log::info('FortifyServiceProvider::register() 実行された');
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
        public function toResponse($request)
        {
            return redirect()->route('verification.notice');
        }
    });

    $this->app->instance(LoginResponse::class, new class implements LoginResponse {
        public function toResponse($request)
        {
            return redirect()->intended('/');
        }
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::ignoreRoutes();//Fortifyのデフォルトルートを無効化
        \Log::info('✅ Fortify::ignoreRoutes() が呼び出されました');
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::loginView(fn () => view('auth.login')); //ログイン画面
        Fortify::registerView(fn () => view('auth.member')); //会員登録画面

        //ログイン制限（ブルートフォース防止）
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    
        //メール認証ルート
    // Route::get('/email/verify', function () {
    //     return view('auth.verify-email');
    // })->name('verification.notice');

    // Route::get('/email/verify/{id}/{hash}',function (EmailVerificationRequest $request) {
    //     $request->fulfill();
    //     return redirect('/mypage/profile');
    // })->middleware(['auth', 'signed'])->name('verification.verify');


    // Route::post('/email/verification-notification', function (Request $request) {
    //     $request->user()->sendEmailVerificationNotification();
    //     return back()->with('message', '認証メールを再送しました。');
    // })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    }
}