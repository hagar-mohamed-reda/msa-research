<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use App\helper\ViewBuilder;

class DoctorCourse extends Model
{


    /**
     * table name of model
     *
     * @var type
     */
    protected $table = "doctor_courses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doctor_id', 'course_id', 'times'
    ];


    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this);

        $doctors = [];
        foreach(User::doctors()->get() as $item)
            $doctors[] = [$item->id, $item->name];

        $courses = [];
        foreach(Course::where('active', 1)->get() as $item)
            $courses[] = [$item->id, $item->name];

        $builder->setPageTitle("user")
                ->setAddRoute(url('/dashboard/doctorcourse/store'))
                ->setEditRoute(url('/dashboard/doctorcourse/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('code'), "editable" => false ])
                ->setCol(["name" => "times", "label" => __('times'), "type" => "number"])

                ->setCol(["name" => "doctor_id", "label" => __('doctor'), "type" => "select", "data" => $doctors])
                ->setCol(["name" => "course_id", "label" => __('course'), "type" => "select", "data" => $courses])
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"])
                ->setUrl(url('/image/doctorcourse'))
                ->build();

        return $builder;
    }
}
