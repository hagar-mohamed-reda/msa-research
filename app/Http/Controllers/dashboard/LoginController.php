<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\helper\Message;
use App\helper\Helper;
use App\LoginHistory;

class LoginController extends Controller {

    /**
     * return login view
     */
    public function index() {
        return view("dashboard.login.login");
    }

    /**
     * return login view
     */
    public function studentLogin() {
        return view("dashboard.login.studentLogin");
    }


    /**
     * login
     */
    public function login(Request $request) { 
        $redirect = $request->type == 'student'? 'students/login' : 'dashboard/login';
        
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ], [
            "phone.required" => __("phone_required"),
            "password.required" => __("password_required"),
        ]);

        if ($validator->fails()) {
            $key = $validator->errors()->first();
            return redirect($redirect . "?status=0&msg=" . $key);
        }
        $error = __("phone or password error");
        
        
        try {
            $user = User::query()
            ->where("phone", $request->phone)
            ->orWhere("username", $request->phone)
            ->orWhere("email", $request->phone)
            //  
            ->where('type', $request->type)
            ->first();

            if ($user) {
                if (!Hash::check($request->password, $user->password)) {
                    return redirect($redirect . "?status=0&msg=$error");
                }
                
                if ($user->active == 0)
                    return redirect($redirect . "?status=0&msg=" . __('your account is not confirmed'));

                Auth::login($user);
                
                LoginHistory::create([
                    'ip' => $request->ip(),
                    'user_id' => $user->id,
                    'phone_details' => LoginHistory::getInfo($request)
                ]);
                
                return redirect($user->type == 'student'? 'students' : 'dashboard');
            }
        } catch (Exception $ex) {}
        return redirect($redirect . "?status=0&msg=$error");
    }

    /**
     * register
     */
    public function register(Request $request) {
        $redirect = $request->type == 'student'? 'students/login' : 'dashboard/login';
        
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
            'password' => 'required', 
        ], [
            "phone.required" => __("phone required"),
            "password.required" => __("password_required"),
        ]);

        if ($validator->fails()) {
            $key = $validator->errors()->first();

            return redirect($redirect . "?status=0&msg=" . $key);
        }
        if ($request->password != $request->confirm_password)
            return redirect($redirect . "?status=0&msg=" . __('passwords not match'));

        if ($request->phone != $request->confirm_phone)
            return redirect($redirect . "?status=0&msg=" . __('phones not match'));

        if ($request->type == 'student')
            $user = User::where('code', $request->code)->where('national_id', $request->national_id)->first();
        else
            $user = User::where('phone', $request->phone)->first();
        
        if (!$user) {
            return redirect($redirect . "?status=0&msg=" . __('code or national_id error'));
        }
        
        try {
            $data = $request->all();
            $data['sms_code'] = rand(11111, 99999);

            $user->update([
                "phone" => $request->phone, 
                "password" => $request->password,  
                "active" => '1'
            ]);
            
            if ($user) {
                if ($user->active == 0)
                    return redirect($redirect . "?status=0&msg=" . __('your account is not confirmed'));

                Auth::login($user);
                
                LoginHistory::create([
                    'ip' => $request->ip(),
                    'user_id' => $user->id,
                    'phone_details' => LoginHistory::getInfo($request)
                ]);
                
                return redirect('dashboard');
            }
            
            // $message = "تم انشاء حسابك بنجاح فى اكادمية المعهد العالى للعلوم الاداريه فى بنى سويف و كود تاكيد الحساب هو " . $data['sms_code'];
            // Helper::sendSms($request->phone, $message); 

        } catch (\Exception $ex) {}
        return redirect($redirect . "?status=0&msg=" . __('there is an error'));
    }

    /**
     * forget password
     */
    public function forgetPassword(Request $request) {
        $redirect = $request->type == 'student'? 'students/login' : 'dashboard/login';
        
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
        ], [
            "phone.required" => __("phone_required"),
        ]);

        if ($validator->fails()) {
            $key = $validator->errors()->first();
            return redirect($redirect . "?status=0&msg=" . $key);
        }
        try {
            $user = User::where("phone", $request->phone)->first();

            if ($user) {
                $newPassword = $user->password;//rand(11111111, 99999999);

                $user->update([
                    "password" => $newPassword
                ]);
                $message = "تم تغير كلمة المرور بنجاح فى اكادميه المعهد العالى للعلوم الاداريه فى بنى سويف وكلمة المرور الجديده هى  " . $newPassword;
                Helper::sendSms($request->phone, $message);
                return redirect($redirect . "?status=1&msg=" . __('your account created please confirm your account'));
            } else {
                return redirect($redirect . "?status=0&msg=" . __('this phone is not exist'));
            }
        } catch (\Exception $ex) {}
        return redirect($redirect . "?status=0&msg=" . __('there is an error'));
    }

    /**
     * confirm account
     */
    public function confirmAccount(Request $request) {
        $redirect = $request->type == 'student'? 'students/login' : 'dashboard/login';
        
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
            'sms_code' => 'required',
        ], [
            "phone.required" => __("phone_required"),
            "sms_code.required" => __("sms_code_required"),
        ]);

        if ($validator->fails()) {
            $key = $validator->errors()->first();
            return redirect($redirect . "?status=0&msg=" . $key);
        }
        try {
            $user = User::where("phone", $request->phone)->first();
            if ($request->sms_code != $user->sms_code)
                return redirect($redirect . "?status=0&msg=" . __('sms code is not correct'));

            if ($user) {
                $user->update([
                    "active" => 1
                ]);
                Auth::login($user);
                return redirect('dashboard');
            } else {
                return redirect($redirect . "?status=0&msg=" . __('this phone or sms code is not correct'));
            }
        } catch (\Exception $ex) {}
        return redirect($redirect . "?status=0&msg=" . __('there is an error'));
    }
    /**
     * logout
     *
     */
    public function logout() {
        if (!Auth::user())
            return back();
            
        $redirect = Auth::user()->type == 'student'? 'students/login' : 'dashboard/login';
        Auth::logout();
        return redirect($redirect);
    }

}
