<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notification;
use App\helper\Message;

use DataTables;
class NotificationController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("dashboard.notification.index");
    }

    /**
     * return json data
     */
    public function getData() {
        return DataTables::eloquent(Notification::query()->where('user_id', Auth::user()->id)->orderBy("created_at", "DESC"))
                        ->addColumn('action', function(Notification $notification) {
                            return view("dashboard.notification.action", compact("notification"));
                        })  
                        ->addColumn('user', function(Notification $notification) {
                            return optional($notification->user)->name;
                        })  
                        ->editColumn('body', function(Notification $notification) {
                            return "قام " . optional($notification->user)->name . " => " . $notification->body;
                        })  
                        ->rawColumns(['action'])
                        ->toJson();
    } 
    
    
    public function get() {
        try {
            $notifications = Notification::where("seen", 0)->where('user_id', Auth::user()->id)->get();
        
            Notification::where("seen", 0)->where('user_id', Auth::user()->id)->update(["seen" => 1]);
            foreach($notifications as $item) {
                $item->body = "قام " . optional($item->user)->name . " => " . $item->body;
            }
            
            return Message::success(Message::$DONE, $notifications);
        } catch (Exception $exc) {
            return Message::success(Message::$DONE, []);
        }
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    { 
        try {  
            $notification->delete();
            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
