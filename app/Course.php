<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;


use App\helper\ViewBuilder;

class Course extends Model
{
     

    protected $table = "courses";

    protected $fillable = [
        'name',	'code',	'hours', 'notes'
    ];
    
    public function studentCourses() {
        return $this->hasMany("App\StudentCourse", "course_id");
    }
    
    public function researches() {
        return $this->hasMany("App\Research", "course_id");
    }
    
    public function departments() {
       /* return Department::join("course_departments", "course_departments.department_id", "departments.id")
        ->where("course_departments.course_id", $this->id);
        */
        $departs = CourseDepartment::where('course_id', $this->id)->pluck("department_id")->toArray();
        return Department::whereIn("id", $departs);
    }
    
    public function doctors() {
       /* return Department::join("course_departments", "course_departments.department_id", "departments.id")
        ->where("course_departments.course_id", $this->id);
        */
        $d = DoctorCourse::where('course_id', $this->id)->pluck("doctor_id")->toArray();
        return User::whereIn("id", $d);
    }
    
    
    /**
     * check if this course assigned to doctor
     * 
     * @return boolean
     */
    public function hasDoctor($doctorId) {
        $doctorAssign = DoctorCourse::where("doctor_id", $doctorId)->where("course_id", $this->id)->first();
        
        if ($doctorAssign)
            return true;
        
        return false;
    }
    
    /**
     * check if this course assigned to doctor
     * 
     * @return boolean
     */
    public function hasStudent($studentId) {
        $assign = StudentCourse::where("student_id", $studentId)->where("course_id", $this->id)->first();
        
        if ($assign)
            return true;
        
        return false;
    }
    
    /**
     * check if this course assigned to department
     * 
     * @return boolean
     */
    public function hasDepartment($departId) {
        $courseDepartment = CourseDepartment::where("department_id", $departId)->where("course_id", $this->id)->first();
        
        if ($courseDepartment)
            return true;
        
        return false;
    }
    
    /**
     * return all doctor of course
     * 
     * @return type
     */
    public function doctorCourses() {
        return $this->hasMany("App\DoctorCourse");
    }
    
    /**
     * return all department of course
     * 
     * @return type
     */
    public function courseDepartments() {
        return $this->hasMany("App\CourseDepartment");
    }
     

    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");
 
        
        $builder->setAddRoute(url('/course/store'))
                ->setEditRoute(url('/course/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "code", "label" => __('code')])
                ->setCol(["name" => "hours", "label" => __('hours'), "type" => "number", "value" => "0"])
                ->setCol(["name" => "notes", "label" => __('notes'), "required" => false])
                ->setUrl(url('/images/course'))
                ->build();

        return $builder;
    }
}
