<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;


use App\helper\ViewBuilder;
 
class StudentGrade extends Model
{
    use SoftDeletes;

    protected $table = "student_grades";

    protected $fillable = [
        'student_id',
        'course_id',	
        'grade',
        'gpa'
    ]; 
    
    public function student() {
        return $this->belongsTo("App\User", "student_id");
    }

    public function course() {
        return $this->belongsTo("App\Course", "course_id");
    }
     
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");
        
        $students = [];
        foreach(User::students()->get() as $item)
            $students[] = [$item->id, $item->name];
        
        $courses = [];
        foreach(Course::all() as $item)
            $courses[] = [$item->id, $item->name];
            
            $builder->setAddRoute(url('/dashboard/studentgrade/store'))
                ->setEditRoute(url('/dashboard/studentgrade/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ])
                ->setCol(["name" => "student_id", "label" => __('student'), 'type' => 'select', 'data' => $students])
                ->setCol(["name" => "course_id", "label" => __('course'), 'type' => 'select', 'data' => $courses]) 
                ->setCol(["name" => "gpa", "label" => __('gpa')]) 
                ->setCol(["name" => "grade", "label" => __('grade')]) 
                ->setUrl(url('/images/studentgrade'))
                ->build();

        return $builder;
    }
}
