<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\helper\ViewBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

class Research extends Model
{

    use SoftDeletes;

    /**
     * table name of model
     *
     * @var type
     */
    protected $table = "researches";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'file', 'doctor_id', 'course_id', 'requirements', 'max_date', 'is_second_period'
    ];
    
    protected $appends = [
        'file_url'
    ];
    
    public function getFileUrlAttribute() {
        return url("file/research") . "/" . $this->file;
    }


    public function course() {
        return $this->belongsTo("App\Course", "course_id");
    }

    public function doctor() {
        return $this->belongsTo("App\User", "doctor_id");
    }


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

        $courses = [
            [null, __("select course")]
        ];
        foreach(Auth::user()->toDoctor()->courses()->get() as $item)
            $courses[] = [$item->id, $item->name];


        $builder->setPageTitle("user")
                ->setAddRoute(url('/dashboard/research/store'))
                ->setEditRoute(url('/dashboard/research/update') . "/" . $this->id)
                //->setCol(["name" => "id", "label" => __('code'), "editable" => false ])
                ->setCol(["name" => "title", "label" => __('title'), "col" => "col-lg-6 col-md-6 col-sm-12"])
                ->setCol(["name" => "max_date", "label" => __('max_date'), "type" => "date", "value" => date('Y-m-d', strtotime('2020-06-15')), "col" => "col-lg-6 col-md-6 col-sm-12"])
                ->setCol(["name" => "file", "label" => __('file'), "type" => "pdf", "col" => "col-lg-6 col-md-6 col-sm-12", "required" => false])
 
                ->setCol(["name" => "course_id", "label" => __('course'), "type" => "select", "data" => $courses, "col" => "col-lg-6 col-md-6 col-sm-12"])
                ->setCol(["name" => "requirements", "label" => __('requirements'), "type" => "textarea", "required" => false, "col" => "col-lg-12 col-md-12 col-sm-12"])
                ->setCol(["name" => "description", "label" => __('description'), "type" => "textarea", "required" => false, "col" => "col-lg-12 col-md-12 col-sm-12"])
                ->setCol(["name" => "created_at", "label" => __('created_at'), "editable" => false ])
                ->setUrl(url('/image/research'))
                ->build();

        return $builder;
    }
}
