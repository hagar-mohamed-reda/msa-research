<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\helper\Message;
use App\helper\Helper;
use App\User;
use App\Doctor;
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
        return DataTables::eloquent(User::where('type', 'doctor'))
                        ->addColumn('action', function(User $doctor) {
                            return view("dashboard.doctor.action", compact("doctor"));
                        })
                        ->addColumn('level_id', function(User $doctor) {
                            return optional($doctor->level)->name;
                        })
                        ->addColumn('department_id', function(User $doctor) {
                            return optional($doctor->department)->name;
                        })
                        ->editColumn('account_confirm', function(User $doctor) {
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
            'phone' => 'required|unique:users',
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
            $data['type'] = 'doctor';
            $doctor = User::create($data);

            notify(__('add doctor'), __('add doctor') . " " . $doctor->name, 'fa fa-user');

            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
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
    public function update(Request $request, User $doctor)
    {
        try {
            $doctor->update($request->all());

            notify(__('edit doctor'), __('edit doctor') . " " . $doctor->name, "fa fa-user");
            return Message::success(Message::$EDIT);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $doctor)
    { 
        try {
            notify(__('remove doctor'), __('remove doctor') . " " . $doctor->name, "fa fa-user");
            $doctor->delete();
            return Message::success(Message::$REMOVE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
