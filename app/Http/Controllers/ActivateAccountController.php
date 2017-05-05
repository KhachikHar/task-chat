<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\ActivateAccount;
use Illuminate\Support\Facades\Mail;

class ActivateAccountController extends Controller
{
    public function index() {
        return view('activateAccount');
    }
    public function activate($token){
        if($token == Auth::user()->token){
            Auth::user()->update(['token' => NULL, 'activate' => 1]);
        }
        return redirect()->route('home');
    }
    public function resend () {
        $token = Auth::user()->token;
        $email = Auth::user()->email;
        Mail::to($email)->send(new ActivateAccount($token));
        return redirect()->route('not.activated.account');
    }
}
