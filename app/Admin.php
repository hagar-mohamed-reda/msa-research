<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \Illuminate\Database\Eloquent\SoftDeletes;

use App\helper\ViewBuilder;

class Admin extends Model
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
 
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");

        $roles = [];
        foreach(Role::all() as $item) {
            $roles[] = [$item->id, $item->name];
        }
        
        $builder->setAddRoute(url('/dashboard/admin/store'))
                ->setEditRoute(url('/dashboard/admin/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ])
                ->setCol(["name" => "name", "label" => __('name')])
                ->setCol(["name" => "phone", "label" => __('phone')])
                ->setCol(["name" => "password", "label" => __('password'), "type" => "password"])
                ->setCol(["name" => "role_id", "label" => __('role'), "type" => "select", "data" => $roles])
                ->setCol(["name" => "active", "label" => __('active'), "type" => "checkbox"])
                ->setUrl(url('/image/doctors'))
                ->build();

        return $builder;
    }
    
}
