<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notifications";

    protected $fillable = [
        'type',
        'notifiable',
        'data',
        'read_at'
    ];
}
