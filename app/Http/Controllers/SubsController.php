<?php

namespace App\Http\Controllers;

use App\Mail\SubscribeEmail;
use App\Models\Subscription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;

class SubsController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email|unique:subscriptions',
        ]);

        $subs = Subscription::add($request->get('email'));
        $subs->generateToken();

        Mail::to($subs)->send(new SubscribeEmail($subs));

        return redirect()->back()->with('status', 'Check your email!');
    }

    public function verify(string $token): Redirector|Application|RedirectResponse
    {
        $subs = Subscription::where('token', $token)->firstOrFail();
        $subs->token = null;
        $subs->save();

        return redirect('/')->with('status', 'Email verified!');
    }
}
