<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Friend extends Model
{
    public function requestTo() {
        return $this->belongsTo('App\User', 'friend_id', 'id')->where('id', '!=', Auth::user()->id);
    }
    public function requestFrom() {
        return $this->belongsTo('App\User', 'user_id', 'id')->where('id', '!=', Auth::user()->id);
    }

}
