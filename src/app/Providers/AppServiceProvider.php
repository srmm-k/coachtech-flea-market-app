<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
// use LaravelLang\Publisher\Provider as LangServiceProvider;
use App\Http\Responses\LoginViewResponse as CustomLoginViewResponse;
use App\Http\Responses\RegisterViewResponse as CustomRegisterViewResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LoginViewResponse::class, CustomLoginViewResponse::class);
        $this->app->singleton(RegisterViewResponse::class,CustomRegisterViewResponse::class);
        // $this->app->register(LangServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
