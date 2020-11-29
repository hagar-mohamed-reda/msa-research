<?php

namespace App;

use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Illuminate\Database\Eloquent\SoftDeletes;

use App\helper\ViewBuilder;

class User  extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

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
        'code',
        'name',
        'sms_code',
        'active',
        'phone',
        'password',
        'username',
        'email',
        'level_id',
        'type',
        'set_number',
        'account_confirm',
        'department_id',
        'national_id',
        'role_id',
        'graduated',
        'can_see_result'
    ];
     

    public function notifications() {
        return $this->hasMany("App\Notification", "user_id");
    }

    public function toDoctor() { 
        $resource = Doctor::find($this->fid);
        return $resource? $resource : $this;
    }
    
    public function loginHistories() {
        return $this->hasMany("App\LoginHistory");
    }

    public function toStudent() {
        $resource = Student::find($this->fid);
        return $resource? $resource : $this;
    }

    public static function students() {
        return Student::query();
        //return User::where('type', 'student');
    }

    public static function doctors() {
        return Doctor::query();
        //return User::where('type', 'doctor');
    }

    public static function admins() {
        return User::where('type', 'admin');
    }
    
    public function researchs() {
        return Research::all();
    }
    
    public function courses() {
        return Course::all();
    }
    
    public function studentResearchs() {
        return StudentResearch::all();
    }



    public function _can($permissionName) {
        try {
            $permission = Permission::where("name", $permissionName)->first(); 
            
            if (!$permission) {
                $permission = Permission::create([
                    "name" => $permissionName
                ]);
            }
            
            
            $role = RoleHasPermission::where("role_id", $this->role_id)->where("permission_id", $permission->id)->first();

            if ($role)
                return true;
        } catch (\Exception $exc) { } 
        return false;
    }


    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");

        $builder->setAddRoute(url('/dashboard/user/store'))
                ->setEditRoute(url('/dashboard/user/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "username", "label" => __('username')])
                ->setUrl(url('/image/users'))
                ->build();

        return $builder;
    }
}
