<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

use Auth;

class NotificationController extends Controller
{
    public function get(){
        $nots = Notification::get();
        $unreadNots = Auth::user()->unreadNotifications;
        $fechaActual = date('Y-m-d');
        foreach($unreadNots as $not){
            if($fechaActual != $not->created_at->toDateString()){
                $not->markAsRead();
            }
        }
        return response()->json( Auth::user()->unreadNotifications);
    }
}
