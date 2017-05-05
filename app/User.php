<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'activate', 'online', 'token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function photo() {
        return $this->hasMany('App\UserPhoto','user_id','id');
    }
    public function mainPhoto() {
        return $this->hasMany('App\UserPhoto','user_id','id')->where( "main", 1 );
    }
    public function requestTo() {
        return $this->hasMany('App\Friend', 'friend_id', 'id')->where( "user_id", Auth::user()->id );
    }
    public function requestFrom() {
        return $this->hasMany('App\Friend', 'user_id', 'id')->where( "friend_id", Auth::user()->id );
    }
    public function unreadMessages() {
        return $this->hasMany('App\Chat', 'from_id', 'id')->where(['to_id' => Auth::user()->id, 'notification' =>0]);
    }
    public function onlineFriendTo() {
        return $this->hasMany('App\Friend', 'friend_id', 'id')->where(["user_id" => Auth::user()->id, 'request' => '1']);
    }
    public function onlineFriendFrom() {
        return $this->hasMany('App\Friend', 'user_id', 'id')->where(['friend_id' => Auth::user()->id, 'request' => '1']);
    }

}
