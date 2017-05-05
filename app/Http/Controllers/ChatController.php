<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index ($id) {
        $messages = Chat::with('userTo.mainPhoto', 'userFrom.mainPhoto')->
                            where(['from_id' => Auth::user()->id, 'to_id' => $id])->
                            orWhere(['from_id' => $id, 'to_id' => Auth::user()->id])->
                            orderBy('id', 'desk')->get()->toArray();
        dd($messages);
//        return view('messages', ['messages' => $messages]);
    }
}
