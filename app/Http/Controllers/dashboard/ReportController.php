<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Problem;
use App\helper\Message;
use App\helper\Helper;
use DB;
use App\Course;
use App\User;
use App\CourseDepartment;
use App\Department;
use App\StudentResearch;
use App\StudentCourse;
use DataTables;

class ReportController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentCourses()
    {
        return view("dashboard.report.studentCourse");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function oneStudentResult(Request $request)
    {
        $query2 = User::students();
        //->join('student_researches', 'student_researches.student_id', '=', 'users.id');

        $search = false;

        if ($request->department_id > 0) {
            $query2->where('department_id', $request->department_id);
            $search = true;
        }

        if ($request->level_id > 0) {
            $query2->where('level_id', $request->level_id);
            $search = true;
        }

        if ($request->student_id > 0) {
            $query2->where('id', $request->student_id);
            $search = true;
        }

        $students = $search? $query2->get() : [];

        return view("dashboard.report.oneStudentResult", compact("students"));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentResult(Request $request)
    {
        $query1 = Course::query();
        $query2 = User::students()->where('graduated', '0');
        //->join('student_researches', 'student_researches.student_id', '=', 'users.id');

        $search = false;

        if ($request->department_id > 0) {
            /*$coursesIds = DB::table('course_departments')
                ->select('id')
                ->where('department_id', $request->department_id)
                ->pluck('id')
                ->toArray();

            $query1->whereIn('id', $coursesIds);*/

            $query2->where('department_id', $request->department_id);
            $search = true;
        }

        if ($request->level_id > 0) {
            $departsIds = DB::table('departments')->select('level_id')->where('level_id', $request->level_id)->pluck('level_id')->toArray();

            if ($request->department_id > 0)
                $departsIds = [$request->department_id];//DB::table('departments')->select('level_id')->where('id', $request->department_id)->where('level_id', $request->level_id);

            $coursesIds = DB::table('course_departments')
                ->select('department_id', 'course_id')
                ->whereIn('department_id', $departsIds)
                ->distinct('course_id')
                ->pluck('course_id')
                ->toArray();

            $query1->whereIn('id', $coursesIds);

            $query2->where('level_id', $request->level_id);
            $search = true;
        }

        if ($request->course_id > 0) {
            $studentIds = DB::table('student_courses')->where('course_id', $request->course_id)->pluck('student_id')->toArray();


            $query1->where('id', $request->course_id);
            $query2->whereIn("id", $studentIds);
            $search = true;
        }

        $coursesIds = $search? $query1->orderBy('id')->pluck('id')->toArray() : [];
        $courses = $search? $query1->orderBy('id')->get() : [];
        $students = $search? $query2->get() : [];

        return view("dashboard.report.studentResult", compact("courses", "students", "coursesIds"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentResult2(Request $request)
    {
        $query1 = DB::table('courses');
        $query2 = DB::table('students')->where('graduated', '0');
        //->join('student_researches', 'student_researches.student_id', '=', 'users.id');

        $search = false;

        if ($request->department_id > 0) {
            /*$coursesIds = DB::table('course_departments')
                ->select('id')
                ->where('department_id', $request->department_id)
                ->pluck('id')
                ->toArray();

            $query1->whereIn('id', $coursesIds);*/

            $query2->where('department_id',  $request->department_id);
            $search = true;
        }

        if ($request->level_id > 0) {
            $departsIds = DB::table('departments')->select('level_id')->where('level_id', $request->level_id)->pluck('level_id')->toArray();

            if ($request->department_id > 0)
                $departsIds = [$request->department_id];//DB::table('departments')->select('level_id')->where('id', $request->department_id)->where('level_id', $request->level_id);

            $coursesIds = DB::table('course_departments')
                ->select('department_id', 'course_id')
                ->whereIn('department_id', $departsIds)
                ->distinct('course_id')
                ->pluck('course_id')
                ->toArray();

            $query1->whereNotIn('id', $coursesIds);

            $query2->where('level_id', $request->level_id);
            $search = true;
        }

        $coursesIds = $search? $query1->orderBy('id')->pluck('id')->toArray() : [];
        $courses = $search? $query1->orderBy('id')->get() : [];
        $students = $search? $query2->get() : [];

        return view("dashboard.report.studentResult2", compact("courses", "students", "coursesIds"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courses(Request $request)
    {

        return view("dashboard.report.course");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function students(Request $request)
    {

        return view("dashboard.report.student");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseDetails(Request $request)
    {
        $query = Course::query()
        ->join("course_departments", "course_departments.course_id", "=", "courses.id")
        ->join("departments", "departments.id", "=", "course_departments.department_id");

        if (Auth::user()->type == 'doctor') {
            $query = Course::query()
            ->join("doctor_courses", "doctor_courses.course_id", "=", "courses.id")
            ->join("course_departments", "course_departments.course_id", "=", "courses.id")
            ->join("departments", "departments.id", "=", "course_departments.department_id")
            ->select('*', 'courses.level_id as level_id')
            ->where('doctor_id', Auth::user()->fid);
        }

        if ($request->level_id > 0) {
            $query->where("courses.level_id", request()->level_id);
        }

        if ($request->department_id > 0) {
            $query->where("department_id", request()->department_id);
        }

        $courses = $query->select('*', 'courses.id as id', 'courses.name as name')->get();
        return view("dashboard.report.courseDetails", compact("courses"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseDetails2(Request $request)
    {
        $query = Course::query()
        ->join("course_departments", "course_departments.course_id", "=", "courses.id")
        ->join("departments", "departments.id", "=", "course_departments.department_id");

        if (Auth::user()->type == 'doctor') {
            $query = Course::query()
            ->join("doctor_courses", "doctor_courses.course_id", "=", "courses.id")
            ->join("course_departments", "course_departments.course_id", "=", "courses.id")
            ->join("departments", "departments.id", "=", "course_departments.department_id")
            ->select('*', 'courses.level_id as level_id')
            ->where('doctor_id', Auth::user()->fid);
        }

        if ($request->level_id > 0) {
            $query->where("courses.level_id", request()->level_id);
        }

        if ($request->department_id > 0) {
            $query->where("department_id", request()->department_id);
        }

        $courses = $query->select('*', 'courses.id as id', 'courses.name as name')->get();
        return view("dashboard.report.courseDetails2", compact("courses"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseResultCount(Request $request)
    {
        $query = Course::query()
        ->join("course_departments", "course_departments.course_id", "=", "courses.id")
        ->join("departments", "departments.id", "=", "course_departments.department_id");

        if (Auth::user()->type == 'doctor') {
            $query = Course::query()
            ->join("doctor_courses", "doctor_courses.course_id", "=", "courses.id")
            ->join("course_departments", "course_departments.course_id", "=", "courses.id")
            ->join("departments", "departments.id", "=", "course_departments.department_id")
            ->where('doctor_id', Auth::user()->fid);
        }

        if ($request->course_id) {
            $query->where('courses.id', $request->course_id);
        }

        if ($request->level_id > 0) {
            $query->where("level_id", request()->level_id);
        }

        if ($request->department_id > 0) {
            $query->where("department_id", request()->department_id);
        }

        $courses = $query->select('*', 'courses.id as id', 'courses.name as name')->get();
        return view("dashboard.report.courseResultCount", compact("courses"));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseResultCount2(Request $request)
    {
        $query = Course::query()
        ->join("course_departments", "course_departments.course_id", "=", "courses.id")
        ->join("departments", "departments.id", "=", "course_departments.department_id");

        if (Auth::user()->type == 'doctor') {
            $query = Course::query()
            ->join("doctor_courses", "doctor_courses.course_id", "=", "courses.id")
            ->join("course_departments", "course_departments.course_id", "=", "courses.id")
            ->join("departments", "departments.id", "=", "course_departments.department_id")
            ->where('doctor_id', Auth::user()->fid);
        }

        if ($request->course_id) {
            $query->where('courses.id', $request->course_id);
        }

        if ($request->level_id > 0) {
            $query->where("level_id", request()->level_id);
        }

        if ($request->department_id > 0) {
            $query->where("department_id", request()->department_id);
        }

        $courses = $query->select('*', 'courses.id as id', 'courses.name as name')->get();
        return view("dashboard.report.couseResultCount2", compact("courses"));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentCourseResearch(Request $request)
    {
        $query = Course::query()
        ->join("course_departments", "courses.id", "=", "course_departments.course_id")
        ->join("departments", "departments.id", "=", "course_departments.department_id");
        $search = false;

        if ($request->level_id > 0) {
            $query->where('level_id', request()->level_id);
            $search = true;
        }

        if ($request->department_id > 0) {
            $query->where('department_id', request()->department_id);
            $search = true;
        }

        if ($request->course_id > 0) {
            $query->where("courses.id", request()->course_id);
            $search = true;
        }

        $courses = $search? $query->select('*', 'courses.name as name', 'courses.id as id')->get() : [];
        return view("dashboard.report.studentCourseResearch", compact("courses"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentsNotUploadResearch(Request $request)
    {
        return view("dashboard.report.studentNotUploadResearch");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentsNotUploadResearchData(Request $request)
    {

         $registerStudentsIdNotHasResearch = StudentCourse::select("student_id")
                        ->distinct("student_id")
                        ->pluck("student_id")
                        ->toArray();


        //return dump($registerStudentsIdNotHasResearch);

        $query = User::query()
            ->where("type", "student")
            ->where("graduated", '0')
            ->whereIn("id", $registerStudentsIdNotHasResearch);

        //return dump($query->get(['id', 'name', 'level_id', 'department_id'])->toArray());

        //return $query->get();
        if ($request->level_id > 0) {
            $query->where("level_id", $request->level_id);
        }

        if ($request->department_id > 0) {
            $query->where("department_id", $request->department_id);
        }


        return DataTables::eloquent($query)
                        ->editColumn('level_id', function(User $user) {
                            return optional($user->level)->name;
                        })
                        ->editColumn('department_id', function(User $user) {
                            return optional($user->department)->name;
                        })
                        ->addColumn('register_course_count', function(User $user) {
                            return $user->toStudent()->courses()->count();
                        })
                        ->addColumn('register_course', function(User $user) {
                            return implode("<li>", $user->toStudent()->courses()->select('name')->pluck('name')->toArray());
                        })
                        ->addColumn('not_uploaded_research_count', function(User $user) {
                            return StudentCourse::where('student_id', $user->id)
                            ->whereNotIn("course_id", $user->toStudent()->researchs()->distinct("course_id")->pluck("course_id")->toArray())
                            ->count();
                        })
                        ->addColumn('not_uploaded_research', function(User $user) {
                            return implode(
                                ", ",
                                Course::whereIn('id', StudentCourse::where('student_id', $user->id)
                            ->whereNotIn("course_id", $user->toStudent()->researchs()->distinct("course_id")->pluck("course_id")->toArray())
                            ->pluck('course_id')->toArray())
                                ->pluck("name")
                                ->toArray());
                        })
                        ->rawColumns(['register_course', 'not_uploaded_research'])
                        ->toJson();
    }
}
