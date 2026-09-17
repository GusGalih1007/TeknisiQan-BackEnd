<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage()
    {
        if (!Auth::check()) {
            return view('auth.login');
        }

        return redirect('/');
    }

    public function login(LoginFormRequest $request)
    {
        $validatedData = $request->validated();
        
        $account = User::where('email', $validatedData['email'])->first();

        if (! $account) {
            return redirect()->back()->with('no-account', 'No account found for this email, ask administrator for an account');
        }

        if (! Hash::check($validatedData['password'], $account->password)) {
            return redirect()->back()->with('invalid-password', 'Password incorrect, please try again');
        }

        auth()->guard('web')->login($account);

        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
