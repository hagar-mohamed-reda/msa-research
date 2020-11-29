<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;


use App\helper\ViewBuilder;
 
class Level extends Model
{ 

    protected $table = "levels";

    protected $fillable = [
        'name'   
    ]; 
    
    public function students() {
        return $this->hasMany("App\User", "level_id");
    }

    public function departments() {
        return $this->hasMany("App\Department", "level_id");
    }
     
    
    /**
     * build view object this will make view html
     *
     * @return ViewBuilder
     */
    public function getViewBuilder() {
        $builder = new ViewBuilder($this, "rtl");
 
        
        $builder->setAddRoute(url('/dashboard/level/store'))
                ->setEditRoute(url('/dashboard/level/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ])
                ->setCol(["name" => "name", "label" => __('name'), "col" => 'col-lg-12 col-md-12 col-sm-12']) 
                ->setUrl(url('/images/level'))
                ->build();

        return $builder;
    }
}
