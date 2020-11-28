<?php

namespace App\Http\Controllers\dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\helper\Message;
use App\Course;
use App\DoctorCourse;
use App\StudentCourse;
use App\StudentResearch;
use App\Research;
use App\CourseDepartment;
use App\User;
use DataTables;

class CourseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (Auth::user()->type == 'student' && Auth::user()->graduated == 1)
            return view("dashboard.student.graduated");
            
        return view("dashboard.course.index");
    }

    /**
     * return json data
     */
    public function getData() {
        $user = Auth::user();
        $query = Course::query();
        
        if ($user->type == 'doctor') {
            $query = $user->toDoctor()->courses();
        }
        
        if ($user->type == 'student') {
            $query = $user->toStudent()->courses();
        }
        
        if (request()->has_research) {
            
            $courseIds = Research::select('course_id')->distinct('course_id')->pluck('course_id')->toArray();
             
            if (request()->has_research == 1)
                $query->whereIn('id', $courseIds);
            else if (request()->has_research == 2) { 
                $query->whereNotIn('id', $courseIds);
            }
        }
        
        if (request()->doctor_id) {
            $courseIds = DoctorCourse::where('doctor_id', request()->doctor_id)->pluck('course_id')->toArray();
            $query->whereIn('id', $courseIds);
            //$query->join("doctor_courses", "doctor_courses.course_id", "=", "courses.id")
            //->where('doctor_id', request()->doctor_id);
            //->select('*', 'courses.id as id');
        }
         
        
        return DataTables::eloquent($query)
                        ->addColumn('action', function(Course $course) {
                            return view("dashboard.course.action", compact("course"));
                        })
                        ->addColumn('departments', function(Course $course) {
                             $departs = "";
                            
                            foreach($course->departments()->get() as $item)
                                $departs .= $item->name . "/" . optional($item->level)->name . ", ";
                            
                            return $departs; 
                        })
                        ->addColumn('researchs', function(Course $course) {
                            return Research::where('course_id', $course->id)->count();
                        })
                        ->addColumn('doctors', function(Course $course) {
                            $doctors = "";
                            
                            foreach($course->doctors()->get() as $item)
                                $doctors .= $item->name . ", ";
                            
                            return $doctors; 
                        })
                        ->addColumn('students', function(Course $course) {
                            return StudentCourse::where('course_id', $course->id)->count();
                        })
                        ->addColumn('student_researches', function(Course $course) {
                            return StudentResearch::where("course_id", $course->id)->count();
                        })
                        ->addColumn('student_not_researches', function(Course $course) {
                            return (StudentCourse::where("course_id", $course->id)->count() - StudentResearch::where("course_id", $course->id)->count());
                        })
                        ->rawColumns(['action'])
                        ->toJson();
    }

    /**
     * Show the form for assign doctors to course.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign(Course $course) {
        return view("dashboard.course.assign", compact("course"));
    }

    /**
     * update doctor coures
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDoctors(Course $course, Request $request) {
        
        // delete old and add new
        $course->doctorCourses()->delete();
        
        for($index = 0; $index < count($request->doctor_id); $index ++) {
            
            if ($request->assign[$index] == 1) {
                DoctorCourse::create([
                    "doctor_id" => $request->doctor_id[$index],
                    "course_id" => $course->id
                ]);
            }
        }
        
        notify(__('assign course'), __('assign course to doctors => ') . " " . $course->name, "fa fa-graduation-cap");
        return Message::success(Message::$DONE);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $course = Course::create($request->all());
            
            for($index = 0; $index < count($request->department_id); $index ++) {
                if ($request->assign[$index] == 1) {
                    CourseDepartment::create([
                        "course_id" => $course->id,
                        "department_id" => $request->department_id[$index]
                    ]);
                }
            }

            notify(__('add course'), __('add course') . " " . $course->name, "fa fa-graduation-cap");
            return Message::success(Message::$DONE);
        } catch (Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Course $course) {
        $query = StudentCourse::query()
            ->join("users", "users.id", "=", "student_courses.student_id")
            ->where('course_id', $course->id);
        
        if ($request->level_id > 0) {
            $query->where('level_id', $request->level_id);
        }
        
        if ($request->department_id > 0) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->course_id > 0) {
            if ($request->has_research == 1) {
                $studentsId = StudentResearch::where('course_id', $request->course_id)->pluck('student_id')->toArray();
                $query->whereIn('student_id', $studentsId);
            }
            
            if ($request->has_research == 0  && $request->has_research != null) {
                $studentsIdHasResearch = StudentResearch::where('course_id', $request->course_id)->pluck('student_id')->toArray();
                //$studentsIdNotHasResearch = StudentCourse::where('course_id', $request->course_id)->whereNotIn('student_id', $studentsIdHasResearch)->pluck('id')->toArray();
                  
                $query->where('users.graduated', 0)->whereNotIn('student_id', $studentsIdHasResearch);
            }
            
            if ($request->has_result == 1) {
                $studentsId = StudentResearch::where('course_id', $request->course_id)->where('result_id', '!=', null)->pluck('student_id')->toArray();
                $query->whereIn('student_id', $studentsId);
            }
            
            if ($request->has_result == 0 && $request->has_result != null) {
                $studentsId = StudentResearch::where('course_id', $request->course_id)->where('result_id', null)->pluck('student_id')->toArray();
                
                $query->whereIn('student_id', $studentsId);
                
            }
            
            if ($request->graduated == 1) {
                $query->where('users.graduated', 1);
            }
        }
        
        $studentCourses = $query->select('*', 'student_courses.id as id')->get();
        
        return view("dashboard.course.show", compact("course", "studentCourses"));
    }
    /**
     * Show the form for assign doctors to course.
     *
     * @return \Illuminate\Http\Response
     */ 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course) {
        return view("dashboard.course.edit", compact("course"));//$course->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course) {
        try {
            $course->update($request->all());

            $course->courseDepartments()->delete();
            for($index = 0; $index < count($request->department_id); $index ++) {
                if ($request->assign[$index] == 1) {
                    CourseDepartment::create([
                        "course_id" => $course->id,
                        "department_id" => $request->department_id[$index]
                    ]);
                }
            }
            notify(__('edit course'), __('edit course') . " " . $course->name, "fa fa-graduation-cap");
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
    public function destroy(Course $course) {
        try {
            notify(__('remove course'), __('remove course') . " " . $course->name, "fa fa-graduation-cap");
            $course->delete();
            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
