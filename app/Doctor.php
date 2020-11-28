<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \Illuminate\Database\Eloquent\SoftDeletes;

use App\helper\ViewBuilder;
use Illuminate\Support\Facades\Auth;

class Doctor extends Model
{
      /**
     * table name of user model
     *
     * @var type
     */
    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'name',
        'sms_code',
        'active',
        'phone',
        'password',
        'confirm_account' 
    ];

    public function courses() {
        return Course::whereIn('id', DoctorCourse::where('doctor_id', $this->id)->pluck('course_id')->toArray());
    }
    
    public function researchs() {
        return $this->hasMany("App\Research", "doctor_id");
    }
    
    public function studentResearchs() {
        $researchIds = Research::where("doctor_id", $this->id)->pluck("id")->toArray();
 
        return StudentResearch::whereIn("research_id", $researchIds);
    }

    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");

        $builder->setAddRoute(url('/dashboard/doctor/store'))
                ->setEditRoute(url('/dashboard/doctor/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "phone", "label" => __('phone')])
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password"])
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"])
                ->setCol(["name" => "account_confirm", "label" => __('confirm_account'), "editable" => false])
                ->setUrl(url('/image/doctors'))
                ->build();

        return $builder;
    }
    
}
