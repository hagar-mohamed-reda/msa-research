<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{

    protected $table = "notifications";

    protected $fillable = [
        'id',
        'title',
        'body',
        'seen',
        'icon',
        'user_id'
    ];

    public static function notify($title, $body='', $icon) {
        Notification::create([
            "title" => $title,
            "body" => $body,
            "seen" => 0,
            "icon" => $icon,
            "user_id" => Auth::user()->id,
        ]);
    }

    public static function notifyUser($title, $body='', $icon, $user) {
        Notification::create([
            "title" => $title,
            "body" => $body,
            "seen" => 0,
            "icon" => $icon,
            "user_id" => $user
        ]);
    }
    public function user() {
        return $this->belongsTo("App\User");
    }

}
