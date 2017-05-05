<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Friend extends Model
{
    public function userTo() {
        return $this->belongsTo('App\User', 'friend_id', 'id')->where('id', '!=', Auth::user()->id);
    }
    public function userFrom() {
        return $this->belongsTo('App\User', 'user_id', 'id')->where('id', '!=', Auth::user()->id);
    }
    public function userToOnline() {
        return $this->belongsTo('App\User', 'friend_id', 'id')->where([['id', '!=', Auth::user()->id], ['online', 1]]);
    }
    public function userFromOnline() {
        return $this->belongsTo('App\User', 'user_id', 'id')->where([['id', '!=', Auth::user()->id], ['online', 1]]);
    }
}
