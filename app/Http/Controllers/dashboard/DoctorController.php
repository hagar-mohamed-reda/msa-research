<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\helper\Message;
use App\helper\Helper;
use App\Doctor;
use App\User;
use DB;
use DataTables;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("dashboard.doctor.index");
    }

    /**
     * return json data
     */
    public function getData() {
        return DataTables::eloquent(Doctor::latest())
                        ->addColumn('action', function(Doctor $doctor) {
                            return view("dashboard.doctor.action", compact("doctor"));
                        })
                        ->addColumn('level_id', function(Doctor $doctor) {
                            return optional($doctor->level)->name;
                        })
                        ->addColumn('department_id', function(Doctor $doctor) {
                            return optional($doctor->department)->name;
                        })
                        ->editColumn('account_confirm', function(Doctor $doctor) {
                            return $doctor->account_confirm == 1? __('yes') : __('no');
                        })
                        ->rawColumns(['action'])
                        ->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|unique:users,phone,'.$request->id,
            'password' => 'required',
        ], [
            "phone.required" => __("phone_required"),
            "phone.unique" => __("phone already exist"),
            "password.required" => __("password_required"),
        ]);

        if ($validator->fails()) {
            $key = $validator->errors()->first();

            return Message::error($key);
        }
        try {
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $data['username'] = $request->phone;
            $data['email'] = $request->phone;
            $doctor = Doctor::create($data);

            // user of doctor
            $user = User::create([
                "name" => $request->name,
                "phone" => $request->phone,
                "username" => $request->phone,
                "email" => $request->phone,
                "password" => bcrypt($request->password),
                "active" => $request->active,
                "type" => "doctor",
                "fid" => $doctor->id,
            ]);

            DB::table('role_user')->insert([
                "role_id" => 3,
                "user_id" => $doctor->id,
                "user_type" => 'App\Doctor',
            ]);
            notify(__('add doctor'), __('add doctor') . " " . $doctor->name, 'fa fa-user');

            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        return $doctor->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|unique:doctors,phone,'.$doctor->id,
            'password' => 'required',
        ], [
            "phone.required" => __("phone_required"),
            "phone.unique" => __("phone already exist"),
            "password.required" => __("password_required"),
        ]);

        if ($validator->fails()) {
            $key = $validator->errors()->first();
            return Message::error($key);
        }
        try {
            $data = $request->all();
            $data['username'] = $request->phone;
            $data['email'] = $request->phone;
            if ($request->password != $doctor->password)
                $data['password'] = bcrypt($request->password);

            $doctor->update($data);
            // update user of doctor
            optional($doctor->user)->update($data);


            notify(__('edit doctor'), __('edit doctor') . " " . $doctor->name, "fa fa-user");
            return Message::success(Message::$EDIT);
        } catch (\Exception $ex) {
            return Message::error($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        try {
            notify(__('remove doctor'), __('remove doctor') . " " . $doctor->name, "fa fa-user");
            $doctor->delete();
            // remove user of doctor
            optional($doctor->user)->delete();
            return Message::success(Message::$REMOVE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
