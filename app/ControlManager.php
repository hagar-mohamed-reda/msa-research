<?php

namespace App;

use Illuminate\Database\Eloquent\Model; 


use App\helper\ViewBuilder;
 
class ControlManager extends Model
{ 

    protected $table = "control_managers";

    protected $fillable = [
        'doctor_id', 'level_id'
    ]; 
    
    public function level() {
        return $this->belongsTo("App\Level");
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
        $builder = new ViewBuilder($this, "rtl");
        $levels = [];
        
        foreach(Level::all() as $item)
            $levels[] = [$item->id, $item->name];
        
        $doctors = [];
        
        foreach(User::doctors()->get() as $item)
            $doctors[] = [$item->id, $item->name];
        
        $builder->setAddRoute(url('/dashboard/controlmanager/store'))
                ->setEditRoute(url('/dashboard/controlmanager/update') . "/" . $this->id)
                ->setCol(["name" => "id", "label" => __('id'), "editable" => false ]) 
                ->setCol(["name" => "level_id", "label" => __('level'), "type" => "select", "data" => $levels, "col" => 'col-lg-12 col-md-12 col-sm-12'])
                ->setCol(["name" => "doctor_id", "label" => __('doctor'), "type" => "select", "data" => $doctors, "col" => 'col-lg-12 col-md-12 col-sm-12']) 
                ->setUrl(url('/images/controlmanager'))
                ->build();

        return $builder;
    }
}
