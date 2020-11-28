<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\helper\Message;
use App\helper\Helper;
use App\StudentResearch;
use App\Research;
use App\Course;
use App\Notification;
use DB;
use DataTables;

class MyResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->type == 'student' && Auth::user()->graduated == 1)
            return view("dashboard.student.graduated");
            
        return view("dashboard.myresearch.index");
    }

    /**
     * return json data
     */
    public function getData() {
         
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
    public function store(Course $course, Request $request)
    { 
        // validate the data
        $validator = validator()->make($request->all(), [
            'file' => 'mimes:pdf|max:10000|required',
            'research_id' => 'required'
        ], [
            "file.required" => __("file_required"), 
            "research.required" => __("please_select_research"), 
            "file.mimes" => __("file type must be pdf"), 
            "file.max" => __("file max size is 10 mb"),  
        ]);
        
        if ($validator->fails()) {
            $key = $validator->errors()->first();  
            return Message::error(__($key));
        }
        try {  
            $research = Research::find($request->research_id);
            
            $studentResearch = StudentResearch::where('student_id', Auth::user()->id)->where('course_id', $course->id)->first();
            
            if (!$studentResearch) {
                
                $studentResearch = StudentResearch::create([
                    "student_id" => Auth::user()->id,
                    "research_id" => $request->research_id,
                    "course_id" => $course->id,
                    "file" => "-",
                    "upload_date" => date("Y-m-d"),
                    "is_second_period" => 1
                ]);
            } else {
                $studentResearch->update([
                    "upload_date" => date("Y-m-d"),
                    "research_id" => $request->research_id,
                    "course_id" => $course->id,
                    "is_second_period" => 1
                ]);
            }
            
            // delete odl file
            if (file_exists(public_path() . "/file/studentresearch/" . $studentResearch->file)) {
                unlink(public_path() . "/file/studentresearch/" . $studentResearch->file);
            }
            
            // upload attachment
            Helper::uploadFile($request->file("file"), "/studentresearch", function($filename) use ($studentResearch){
                $studentResearch->update([
                    "file" => $filename
                ]);
            });
            
            // notify the doctor
            Notification::notifyUser(__('new uploaded research'), __('new upload research from '). Auth::user()->name, "fa fa-newspaper-o", $research->doctor_id);
            
            
            
            notify(__('your upload a research'), __('your upload research ') . " " . $research->title, 'fa fa-newspaper-o');

            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR . $ex->getMessage());
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $studentResearch = StudentResearch::where('course_id', $course->id)->where('student_id', Auth::user()->id)->first();
        return view("dashboard.myresearch.show", compact("course", "studentResearch"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return view("dashboard.myresearch.edit", compact("course"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentResearch $studentresearch)
    {
        try {
            $studentresearch->update($request->all());

            notify(__('edit studentresearch'), __('edit studentresearch') . " " . $studentresearch->name, "fa fa-bank");
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
    public function destroy(StudentResearch $studentresearch)
    {
         
        try {
            notify(__('remove studentresearch'), __('remove studentresearch') . " " . $studentresearch->name, "fa fa-bank");
            $studentresearch->delete();
            return Message::success(Message::$REMOVE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
