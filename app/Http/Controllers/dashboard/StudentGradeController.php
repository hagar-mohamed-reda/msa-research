<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\imports\StudentGradeImporter;
use App\helper\Message;
use App\helper\Helper;
use App\StudentGrade;
use DB;
use DataTables;
use Excel;

class StudentGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("dashboard.studentgrade.index");
    }
    
    public function showForStudent() {
        return view("dashboard.studentgrade.show");
    }

    /**
     * return json data
     */
    public function getData() {
        $query = StudentGrade::query();
        
        if (request()->student_id > 0) {
            $query->where('student_id', request()->student_id);
        }
        
        return DataTables::eloquent($query)
                        ->addColumn('action', function(StudentGrade $studentgrade) {
                            return view("dashboard.studentgrade.action", compact("studentgrade"));
                        })
                        ->editColumn('student_id', function(StudentGrade $studentgrade) {
                            return optional($studentgrade->student)->name;
                        })
                        ->editColumn('course_id', function(StudentGrade $studentgrade) {
                            return optional($studentgrade->course)->name;
                        }) 
                        ->addColumn('set_number', function(StudentGrade $studentgrade) {
                            return optional($studentgrade->student)->set_number;
                        }) 
                        ->addColumn('code', function(StudentGrade $studentgrade) {
                            return optional($studentgrade->student)->code;
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
     * import products from excel file
     * 
     * @param Request $request
     * @return type
     */
    public function import(Request $request) {
        
        Excel::import(new StudentGradeImporter, $request->file('grades'), 's3');
        
        return Message::success(Message::$DONE);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $studentgrade = StudentGrade::create($request->all());

            notify(__('add studentgrade'), __('add studentgrade') . " " . $studentgrade->name, 'fa fa-newspaper-o');

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
    public function edit(StudentGrade $studentgrade)
    {
        return $studentgrade->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentGrade $studentgrade)
    {
        try {
            $studentgrade->update($request->all());

            notify(__('edit studentgrade'), __('edit studentgrade') . " " . $studentgrade->name, "fa fa-newspaper-o");
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
    public function destroy(StudentGrade $studentgrade)
    {
        if ($studentgrade->students()->count() > 0)
            return Message::success(__("can't remove studentgrade has students"));
            
        if ($studentgrade->departments()->count() > 0)
            return Message::success(__("can't remove studentgrade has departments"));
        
        try {
            notify(__('remove studentgrade'), __('remove studentgrade') . " " . $studentgrade->name, "fa fa-newspaper-o");
            $studentgrade->delete();
            return Message::success(Message::$REMOVE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
