<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use \Illuminate\Database\Eloquent\SoftDeletes;

use App\helper\ViewBuilder;

class Student extends Model
{

    /**
     * table name of user model
     *
     * @var type
     */
    protected $table = "students";

    public static $STUDENT_STORE_API = "http://lms.seyouf.sphinxws.com/api/student-store";
    public static $STUDENT_UPDATE_API = "http://lms.seyouf.sphinxws.com/api/student-update";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'name',
        'set_number',
        'sms_code',
        'active',
        'phone',
        'username',
        'email',
        'password',
        'national_id',
        'level_id',
        'type',
        'account_confirm',
        'department_id',
        'graduated',
        'can_see_result'
    ];

    public function user() {
        return $this->hasOne("App\User", "fid");
    }

    public function toStudent() {
        return $this;
    }

    public function grades() {
        return $this->hasMany('App\StudentGrade', 'student_id');
    }

    public function getResult($course) {
        //$researchsId = Research::where('course_id', $course)->pluck('id')->toArray();
        return StudentResearch::where('course_id', $course)->where('student_id', $this->id)->first();
    }

    public function department() {
        return $this->belongsTo("App\Department");
    }

    public function level() {
        return $this->belongsTo("App\Level");
    }

    public function courses() {
        return Course::whereIn('id', StudentCourse::where('student_id', $this->id)->pluck('course_id')->toArray());
    }

    public function researchs() {
        $courseIds = $this->courses()->pluck("id")->toArray();
        //return $courseIds;
        return Research::whereIn("course_id", $courseIds);
    }

    public function myResearchs() {
        return $this->hasMany("App\StudentResearch", "student_id");
    }

    public function IsUploadFileForResearch($research) {
        $file = StudentResearch::where('research_id', $research)->where("student_id", $this->id)->first();

        return $file? true : false;
    }

    public function IsUploadFileForCourse($course) {
        $file = StudentResearch::where('course_id', $course)->where("student_id", $this->id)->first();


        if (!$file)
            return false;

        $rs = Research::find($file->research_id);

        $today = strtotime(date('Y-m-d'));
        $maxDate = strtotime($rs->max_date/*. ' + 7 days'*/);

        if ($file) {
            if ($file->result_id != null)
                return true;
        }

        if ($today > $maxDate) {
            return true;
        }


        return false;
    }

    public function getUploadedFileForResearch($research) {
        $file = StudentResearch::where('research_id', $research)->where("student_id", $this->id)->first();
        return $file;
    }

    public function getUploadedFileForCourse($course) {
        $file = StudentResearch::where('course_id', $course)->where("student_id", $this->id)->first();
        return $file;
    }
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");

        $levels = [];

        foreach(Level::all() as $item)
            $levels[] = [$item->id, $item->name];

        $departments = [];

        foreach(Department::all() as $item)
            $departments[] = [$item->id, $item->name . "-" . optional($item->level)->name];

        $builder->setAddRoute(url('/dashboard/student/store'))
                ->setEditRoute(url('/dashboard/student/update') . "/" . $this->id)
                //->setCol(["name" => "id", "label" => __('id'), "editable" => false])
                ->setCol(["name" => "code", "label" => __('code')])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "national_id", "label" => __('national_id'), "required" => false])
                ->setCol(["name" => "set_number", "label" => __('set_number'), "required" => false])
                ->setCol(["name" => "phone", "label" => __('phone'), "required" => false])
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password", "required" => false])
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"])
                ->setCol(["name" => "level_id", "label" => __('level'), "type" => "select", "data" => $levels, "col" => 'col-lg-12 col-md-12 col-sm-12'])
                ->setCol(["name" => "department_id", "label" => __('department'), "type" => "select", "data" => $departments, "col" => 'col-lg-12 col-md-12 col-sm-12'])

                //->setCol(["name" => "sms_code", "label" => __('sms_code'), "editable" => false])
                ->setCol(["name" => "account_confirm", "label" => __('confirm_account'), "editable" => false])
                ->setCol(["name" => "graduated", "label" => __('graduated'), "editable" => false])
                ->setCol(["name" => "can_see_result", "label" => __('can_see_result'), 'type' => 'checkbox'])
                ->setUrl(url('/image/students'))
                ->build();

        return $builder;
    }
}
