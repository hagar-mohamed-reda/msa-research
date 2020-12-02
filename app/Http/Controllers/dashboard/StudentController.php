<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\helper\Message;

use App\imports\StudentImporter;
use App\imports\EditStudentImporter;
use App\imports\EditNationalIdStudent;
use App\imports\EditGraduatedStudent;

use App\helper\Helper;
use App\Student;
use App\User;
use App\Course;
use App\StudentCourse;
use DB;
use DataTables;
use Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("dashboard.student.index");
    }

    /**
     * return json data
     */
    public function getData() {
        $query = Student::query();

        if (request()->level_id > 0) {
            $query->where('level_id', request()->level_id);
        }

        if (request()->department_id > 0) {
            $query->where('department_id', request()->department_id);
        }

        if (request()->un_complete == 1) {
            $studentIds = StudentCourse::select('student_id')->distinct('student_id')->pluck('student_id')->toArray();
            $query->whereNotIn('id', $studentIds);
        }

        return DataTables::eloquent($query)
                        ->addColumn('action', function(Student $student) {
                            return view("dashboard.student.action", compact("student"));
                        })
                        ->addColumn('level_id', function(Student $student) {
                            return optional($student->toStudent()->level)->name;
                        })
                        ->addColumn('department_id', function(Student $student) {
                            return optional($student->toStudent()->department)->name;
                        })
                        ->addColumn('courses', function(Student $student) {
                            return StudentCourse::where('student_id', $student->id)->count();
                        })
                        ->editColumn('account_confirm', function(Student $student) {
                            return $student->account_confirm == 1? __('yes') : __('no');
                        })
                        ->editColumn('graduated', function(Student $student) {
                            return $student->graduated == 1? __('yes') : __('no');
                        })
                        ->rawColumns(['action'])
                        ->toJson();
    }

    /**
     * return json data
     */
    public function getData2() {
        //$course = Course::find(request()->course_id);

        //return $course;

        return DataTables::eloquent(Student::latest())
                        ->addColumn('action', function(Student $student) {
                            return view("dashboard.course.student", compact("student"));
                        })
                        ->addColumn('level', function(Student $student) {
                            return optional($student->toStudent()->level)->name;
                        })
                        ->addColumn('department', function(Student $student) {
                            return optional($student->toStudent()->department)->name;
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

        if ($request->national_id) {
            if (Student::where('national_id', $request->national_id)->first()) {
                return Message::error(__('national id already exist'));
            }
        }

        try {
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $data['username'] = $request->phone;
            $data['email'] = $request->phone;
            $student = Student::create($data);

            // user of student
            $user = User::create([
                "name" => $request->name,
                "phone" => $request->phone,
                "username" => $request->phone,
                "email" => $request->phone,
                "password" => bcrypt($request->password),
                "active" => $request->active,
                "type" => "student",
                "fid" => $student->id,
            ]);

            DB::table('role_user')->insert([
                "role_id" => 4,
                "user_id" => $student->id,
                "user_type" => 'App\Student',
            ]);
            notify(__('add student'), __('add student') . " " . $student->name, 'fa fa-user');

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
    public function show(Student $student) {
        return view("dashboard.student.show", compact("student"));
    }

    /**
     * import products from excel file
     *
     * @param Request $request
     * @return type
     */
    public function import(Request $request) {
        if ($request->type == 'edit')
            Excel::import(new EditStudentImporter, $request->file('users'));

        else if ($request->type == 'new')
            Excel::import(new StudentImporter, $request->file('users'));

        else if ($request->type == 'edit_national_id')
            Excel::import(new EditNationalIdStudent, $request->file('users'));

        else if ($request->type == 'edit_graduated')
            Excel::import(new EditGraduatedStudent, $request->file('users'));

        return Message::success(Message::$DONE);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return $student->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {

        $validator = validator()->make($request->all(), [
            'phone' => 'required|unique:students,phone,'.$student->id,
            'national_id' => 'required|unique:students,national_id,'.$student->id,
            'password' => 'required',
        ], [
            "phone.required" => __("phone_required"),
            "phone.unique" => __("phone already exist"),
            "national_id.unique" => __('national id already exist'),
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
            if ($request->password != $student->password)
                $data['password'] = bcrypt($request->password);

            $student->update($data);
            // update user of doctor
            optional($student->user)->update($data);

            notify(__('edit student'), __('edit student') . " " . $student->name, "fa fa-user");
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
    public function destroy(Student $student)
    {
        try {
            notify(__('remove student'), __('remove student') . " " . $student->name, "fa fa-user");
            $student->delete();
            optional($student->user)->delete();
            return Message::success(Message::$REMOVE);
        } catch (\Exception $ex) {
            return Message::error($ex->getMessage());
        }
    }
}
