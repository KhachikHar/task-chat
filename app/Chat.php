<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    public function userTo() {
        return $this->belongsTo('App\User', 'to_id', 'id');
    }
    public function userFrom() {
        return $this->belongsTo('App\User', 'from_id', 'id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
