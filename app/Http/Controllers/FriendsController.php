<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Response;

class FriendsController extends Controller
{
    public function index(Request $request) {
        $id = Auth::user()->id;
        $friends = Friend::with('requestFrom.mainPhoto', 'requestTo.mainPhoto')->
                            where(['friend_id' => $id, 'request' => 1])->
                            orWhere(['user_id' => $id, 'request' => 1])->get()->toArray();
        return view('friends', ['friends' => $friends]);
    }

    public function addFriend(Request $request){
        if ($request->ajax()) {
            $id = $request->input('user_id');
            if(!empty($id)){
                $friend = new Friend();
                if( !empty(User::where(['id' => $id])->get()->toArray()) && $id != Auth::user()->id ){
                    $friend->{'user_id'} = Auth::user()->id;
                    $friend->{'friend_id'} = $id;
                    $friend->save();
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

    public function confirmFriend(Request $request) {
        if ($request->ajax()) {
            $id = $request->input('id');
            if(!empty($id)){
                if( !empty(Friend::where(['user_id' => $id, 'friend_id' => Auth::user()->id])->get()->toArray()) ){
                    Friend::where(['user_id' => $id, 'friend_id' => Auth::user()->id])->update(['request_notification' => 1, 'request' => 1]);
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

    public function deleteFriendRequest(Request $request) {
        if ($request->ajax()) {
            $id = $request->input('id');
            if(!empty($id)){
                if( !empty(Friend::where(['user_id' => $id, 'friend_id' => Auth::user()->id])->get()->toArray()) ){
                    Friend::where(['user_id' => $id, 'friend_id' => Auth::user()->id])->delete();
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

    public function requests() {
        $authId = Auth::user()->id;
        $requests = Friend::with('requestFrom.mainPhoto')->where(['friend_id' => $authId, 'request' => 0])->get()->toArray();
        Friend::where(['request_notification' => 0, 'friend_id' => $authId])->update(['request_notification' => 1]);
        return view('friendRequests', ['requests' => $requests]);
    }
}
