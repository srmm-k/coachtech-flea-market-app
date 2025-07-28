<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;


class CustomRegisterController extends Controller
{
    public function showRegisterForm()
    {

        return view('auth.member');
    }

    public function register(Request $request)
    {
        $user = app(CreateNewUser::class)->create($request->all());

        
        event(new Registered($user));
        Auth::login($user, true);
        return redirect()->route('verify.email.info');
    }
}
