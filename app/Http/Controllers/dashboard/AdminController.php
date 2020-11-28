<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\helper\Message;
use App\helper\Helper;
use App\User;
use App\Admin;
use DB;
use DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("dashboard.admin.index");
    }

    /**
     * return json data
     */
    public function getData() {
        return DataTables::eloquent(User::admins())
                        ->addColumn('action', function(User $admin) {
                            return view("dashboard.admin.action", compact("admin"));
                        })
                        ->addColumn('level_id', function(User $admin) {
                            return optional($admin->level)->name;
                        })
                        ->addColumn('department_id', function(User $admin) {
                            return optional($admin->department)->name;
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
            $data['type'] = 'admin';
            $admin = User::create($data);

            notify(__('add admin'), __('add admin') . " " . $admin->name, 'fa fa-user');

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
    public function edit(Admin $admin)
    {
        return $admin->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $admin)
    {
        try {
            $admin->update($request->all());

            notify(__('edit admin'), __('edit admin') . " " . $admin->name, "fa fa-user");
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
    public function destroy(User $admin)
    { 
        try {
            notify(__('remove admin'), __('remove admin') . " " . $admin->name, "fa fa-user");
            $admin->delete();
            return Message::success(Message::$REMOVE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
