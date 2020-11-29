<?php

namespace App\Http\Controllers\dashboard;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use Illuminate\Support\Facades\Auth;
use App\User;

class StudentRemoteLoginController extends Controller {

    public function __construct() {
        
    }

    /**
     * remote login with api token
     * check on api token if it's exist and redirect to home page
     * 
     */
    public function login(Request $request) {
        $user = User::where('api_token', $request->api_token)->first();
        if ($user) {
            // login for the user
            Auth::login($user);
            
            if ($user->type == 'student') {
                return $this->redirectAsStudent($user);
            } else if ($user->type == 'doctor') {
                return $this->redirectAsDoctor($user);
            } else if ($user->type == 'admin')  {
                return $this->redirectAsAdmin($user);
            }
        }
        return redirect(route('studentLogin'));
    }

    public function redirectAsStudent(User $user) {
        return redirect('/students');
    }

    public function redirectAsDoctor(User $user) {
        return redirect('/dashboard');
    }

    public function redirectAsAdmin(User $user) {
        return redirect('/dashboard');
    }

}
