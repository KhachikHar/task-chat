<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(Request $request){
        $users = User::with('mainPhoto', 'friendTo', 'friendFrom')->where('id', '!=', Auth::user()->id)->get()->toArray();
        return view('users', ['users' => $users]);
    }
}
