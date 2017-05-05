<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use App\UserPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image as Image;

class HomeController extends Controller
{
    public function index(){
        $id =  Auth::user()->id;
        if(Auth::user()->online == 0){
            Auth::user()->update(['online' => 1]);
        }
        $photo = UserPhoto::with('user')->where(['user_id' => $id, 'main' => 1])->get()->toArray();
        $friendRequests = count(Friend::where(['request_notification' => 0, 'friend_id' => $id])->get()->toArray());
        $onlineFriends = User::with('onlineFriendFrom', 'onlineFriendTo', 'mainPhoto', 'unreadMessages')->
                            has('onlineFriendFrom')->where('online', 1)->
                            orHas('onlineFriendTo')->where('online', 1)->get()->toArray();
        return view('home', ['photo' => $photo, 'friendRequests' => $friendRequests, 'onlineFriends' => $onlineFriends]);
    }
    public function newPhoto (Request $request) {
        if(!empty($request->file('user_photo'))){
            $photo = new UserPhoto();
            $file = $request->file('user_photo');
            $lower_file_name = mb_strtolower($file->getClientOriginalName());
            $file_format = explode('.', $lower_file_name);
            $ext = end($file_format);
            $filename = time().'.'.$ext;
            $user_photo = Image::make($file)->save(public_path('images/user_photo/' . $filename));
            if($user_photo) {
                $photo->{'user_id'} = Auth::user()->id;
                $photo->{'photo'} = '/images/user_photo/' . $filename;
                if(empty(UserPhoto::where('user_id', Auth::user()->id)->get()->toArray())){
                    $photo->{'main'} = 1;
                } else {
                    $photo->{'main'} = 0;
                }
                $photo->save();
                return Redirect::back();
            } else {
                dd('error !!!');
            }

        } else {
            dd('error !!!');
        }
    }
    public function logout(Request $request) {
        Auth::user()->update(['online' => 0]);
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }
}
