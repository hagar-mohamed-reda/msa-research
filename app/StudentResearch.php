<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use App\helper\ViewBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class StudentResearch extends Model
{


    use SoftDeletes;

    /**
     * table name of model
     *
     * @var type
     */
    protected $table = "student_researches";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id', 'research_id', 'file', 'result_id', 'upload_date', 'publish', 'course_id', 'admin_publish', 'is_second_period'
        // result => ['failed', 'success', 'd', 'f', 'pending']
    ];
    
    protected $appends = [
        'file_url'
    ];
    
    public function getFileUrlAttribute() {
        return url("file/studentresearch") . "/" . $this->file;
    }
    
    public function canEditResult() {
        if ($this->publish == 1) {
            return false;
        }
        return true;
    }
    
    public function research() {
        return $this->belongsTo("App\Research", "research_id");
    }
    
    public function course() {
        return $this->belongsTo("App\Course", "course_id");
    }
    
    public function student() {
        return $this->belongsTo("App\Student", "student_id");
    }
    
    public function result() {
        return $this->belongsTo("App\Result", "result_id");
    }
    
    public static function newResearch($doctor = null) { 
        $query = StudentResearch::where('is_second_period', 1);
        
        if ($doctor) {
            $coursesIds = DB::table('doctor_courses')->where('doctor_id', $doctor)->pluck('course_id')->toArray();
            $query->whereIn('course_id', $coursesIds);
        }
        
        return $query;
    }
    
    public function results() {
        $studentCourse = StudentCourse::where('course_id', optional($this->research)->course_id)->where('student_id', $this->student_id)->first();
        
        if ($studentCourse) {
            if ($studentCourse->times <= 1) {
                return Result::whereIn('id', [1, 2])->get();
            } 
        }
        
        return Result::whereNotIn("id", [1, 2])->get();
    }


    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this);

        $builder->setPageTitle("user")
                ->setAddRoute(url('/dashboard/research/store'))
                ->setEditRoute(url('/dashboard/research/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('code'), "editable" => false ])
                ->setCol(["name" => "title", "label" => __('title')])
                ->setCol(["name" => "max_date", "label" => __('max_date'), "type" => "date", "value" => date('Y-m-d')])

                ->setCol(["name" => "requirements", "label" => __('requirements'), "type" => "textarea", "required" => false])
                ->setCol(["name" => "description", "label" => __('description'), "type" => "textarea", "required" => false])
                ->setCol(["name" => "file", "label" => __('file'), "type" => "pdf"])
                ->setUrl(url('/image/research'))
                ->build();

        return $builder;
    }
}
