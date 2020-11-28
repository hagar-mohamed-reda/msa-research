<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseDepartment extends Model
{
    
    protected $table = "course_departments";

    protected $fillable = [
        'course_id', 'department_id'
    ];
}
