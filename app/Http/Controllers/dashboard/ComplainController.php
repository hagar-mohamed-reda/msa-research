<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Problem;
use App\helper\Message;
use App\helper\Helper; 
use DB;
use DataTables;
use App\Department;
use App\Level; 
use App\User;

class ComplainController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function student()
    {
        return view("dashboard.student_problem.index");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doctor()
    {
        return view("dashboard.doctor_problem.index");
    }
    /**
     * return json data
     */
    public function getDataStudent() {
        $query = Problem::query()->where('type', 'student');
        
        if (request()->status)
            $query->where('status', request()->status);
        else
            $query->where('status', 'default');
        
        if (request()->department_id) {
            $codes = User::where('department_id', request()->department_id)->pluck('code')->toArray();
            $codesList = [];
            foreach($codes as $code)
                $codesList[] = str_replace(" ", "", $code);
                 
            $query->whereIn('code', $codesList);
        }
        
        if (request()->level_id) { 
            $codes = User::where('level_id', request()->level_id)->pluck('code')->toArray();
            $codesList = [];
            foreach($codes as $code)
                $codesList[] = str_replace(" ", "", $code);
                 
            $query->whereIn('code', $codesList);
        }
        
        return DataTables::eloquent($query)
                        ->addColumn('action', function(Problem $problem) {
                            return view("dashboard.student_problem.action", compact("problem"));
                        })
                         ->editColumn('user_id', function(Problem $problem) {
                            return optional($problem->user)->name;
                        })
                        ->rawColumns(['action'])
                        ->toJson();
    }
    /**
     * return json data
     */
    public function getDataDoctor() {
        $query = Problem::query()->where('type', 'doctor');
        
        if (request()->status)
            $query->where('status', request()->status);
        else
            $query->where('status', 'default');
        
        return DataTables::eloquent($query)
                        ->addColumn('action', function(Problem $problem) {
                            return view("dashboard.doctor_problem.action", compact("problem"));
                        })
                         ->editColumn('user_id', function(Problem $problem) {
                            return optional($problem->user)->name;
                        })
                        ->rawColumns(['action'])
                        ->toJson();
    }

    
    public function store(Request $request) {

        $redirect = $request->type == 'student'? 'students/login' : 'dashboard/login';
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
            'name' => 'required',
            'type' => 'required',
            'notes' => 'required',
        ], [
            "phone.required" => __("phone_required"),
            "name.required" => __("name_required"),
            "type.required" => __("type_required"),
            "notes.required" => __("your must write your complaint"),
        ]);
        
        $redirect = $request->type == 'student'? 'students/login' : 'dashboard/login';

        if ($validator->fails()) {
            $key = $validator->errors()->first();
            return redirect($redirect . "?status=0&msg=" . $key);
        }

        $problem = Problem::create($request->all());

        return redirect($redirect . "?status=1&msg=" . __('your complaint sent to admin'));
    }
     
     
    public function update(Problem $problem, Request $request) {  
 
        try {
            $data = $request->all();
            $data['user_id'] = Auth::user()->id;
            
            $problem->update($data);

            notify(__('update complaint'), __('update complaint for ') . " " . $problem->name, "fa fa-frown-o");
            return Message::success(Message::$EDIT);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
    
}
