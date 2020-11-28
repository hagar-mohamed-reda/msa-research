<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use App\helper\ViewBuilder;

class StudentCourse extends Model
{


    /**
     * table name of model
     *
     * @var type
     */
    protected $table = "student_courses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id', 'course_id', 'times'
    ];
    
    public function student() {
        return $this->belongsTo("App\Student", "student_id");
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
        $builder = new ViewBuilder($this);

        $students = [];
        foreach(User::students()->get() as $item)
            $students[] = [$item->id, $item->name];

        $courses = [];
        foreach(Course::get() as $item)
            $courses[] = [$item->id, $item->name];

        $builder->setPageTitle("user")
                ->setAddRoute(url('/dashboard/studentcourse/store'))
                ->setEditRoute(url('/dashboard/studentcourse/update') . "/" . $this->id)
                //->setCol(["name" => "id", "label" => __('code'), "editable" => false ])

                ->setCol(["name" => "student_id", "label" => __('student'), "type" => "select", "data" => $students])
                ->setCol(["name" => "course_id", "label" => __('course'), "type" => "select", "data" => $courses])
                ->setCol(["name" => "times", "label" => __('times'), "type" => "number"])
                ->setUrl(url('/image/studentcourse'))
                ->build();

        return $builder;
    }
}
