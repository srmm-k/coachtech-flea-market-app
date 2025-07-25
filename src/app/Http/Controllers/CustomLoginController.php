<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user || !\Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません'],
            ]);
        }

        //未認証チェック
        if (! $user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['メールアドレスが認証されていません。メールをご確認ください。'],
            ]);
        }

        Auth::login($user, $request->filled('remember'));
        return redirect()->intended('/');
    }
}
