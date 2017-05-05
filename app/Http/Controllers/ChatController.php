<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Friend;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ChatController extends Controller
{
    public function index ($id) {
        $messages = Chat::with('userFrom.mainPhoto')->
                            where(['from_id' => Auth::user()->id, 'to_id' => $id])->
                            orWhere(['from_id' => $id, 'to_id' => Auth::user()->id])->get()->toArray();
        Chat::where(['from_id' => $id, 'to_id' => Auth::user()->id, 'notification' => 0])->update(['notification' => 1]);
        Chat::where(['from_id' => Auth::user()->id, 'to_id' => $id, 'selected_from' => 0])->update(['selected_from' => 1]);
        Chat::where(['from_id' => $id, 'to_id' => Auth::user()->id, 'selected_to' => 0])->update(['selected_to' => 1]);
        return view('messages', ['messages' => $messages, 'id' => Auth::user()->id, 'friendId' => $id]);
    }

    public function sendMessage (Request $request) {
        if ($request->ajax()) {
            $id = Auth::user()->id;
            $message = $request->input('message');
            $friendId = $request->input('friend_id');
            if(!empty($message) && !empty($friendId)){
                if( !empty(Friend::where(['user_id' => $id, 'friend_id' => $friendId, 'request' => 1])->orWhere(['user_id' => $friendId, 'friend_id' => $id, 'request' => 1])->get()->toArray()) ){
                    $chat = new Chat();
                    $chat->{'from_id'} = $id;
                    $chat->{'to_id'} = $friendId;
                    $chat->{'message'} = $message;
                    $chat->{'check'} = 0;
                    $chat->{'notification'} = 0;
                    $chat->{'selected_from'} = 0;
                    $chat->{'selected_to'} = 0;
                    $chat->save();
                    return Response::json(['success' => true, 'error' => '']);
                } else {
                    return Response::json(['success' => false, 'error' => 'error']);
                }
            } else {
                return Response::json(['success' => false, 'error' => 'error']);
            }
        } else {
            throw new MethodNotAllowedException(['POST'], 'Method is not allowed');
        }
    }

    public function selectMessage(Request $request) {
        if ($request->ajax()) {
            $id = Auth::user()->id;
            $friendId = $request->input('friend_id');
            if(!empty($friendId)){
                if( !empty(Friend::where(['user_id' => $id, 'friend_id' => $friendId, 'request' => 1])->orWhere(['user_id' => $friendId, 'friend_id' => $id, 'request' => 1])->get()->toArray()) ){
                    $now = Carbon::now();
                    $now->second -= 6;
                    $time = $now->toDateTimeString();
                    $newMessages = Chat::with('userFrom.mainPhoto')->
                                    where(['from_id' => $id, 'to_id' => $friendId, 'selected_from' => 0], ['created_at', '>=', $time])->
                                    orWhere(['from_id' => $friendId, 'to_id' => $id, 'selected_to' => 0], ['created_at', '>=', $time])->get()->toArray();
                    if(!empty($newMessages)){
                        $last = $newMessages[count($newMessages)-1]['id'];
                        Chat::where(['from_id' => $id, 'to_id' => $friendId, 'selected_from' => 0], ['id', '<=', $last])->update(['selected_from' => 1]);
                        Chat::where(['from_id' => $friendId, 'to_id' => $id, 'selected_to' => 0], ['id', '<=', $last])->update(['selected_to' => 1]);
                    }
                    return Response::json(['success' => true, 'error' => '', $newMessages]);
                } else {
                    return Response::json(['success' => false, 'error' => 'error']);
                }
            } else {
                return Response::json(['success' => false, 'error' => 'error']);
            }
        } else {
            throw new MethodNotAllowedException(['POST'], 'Method is not allowed');
        }
    }
}
