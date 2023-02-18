<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerForm(): Factory|View|Application
    {
        return view('pages.register');
    }

    public function register(Request $request): Redirector|Application|RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::add($request->all());
        $user->generatePassword($request->get('password'));

        return redirect('/login');
    }

    public function loginForm(): Factory|View|Application
    {
        return view('pages.login');
    }

    public function login(Request $request): Redirector|Application|RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]))
        {
            return redirect('/');
        }
        return redirect()->back()->with('status', 'Wrong login or password');
    }

    public function logout(): Redirector|Application|RedirectResponse
    {
        Auth::logout();
        return redirect('/login');
    }
}
