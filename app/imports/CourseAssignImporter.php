<?php

namespace App\imports; 

use Maatwebsite\Excel\Concerns\ToModel; 
use App\StudentCourse;
use App\User;

class CourseAssignImporter  implements ToModel
{
    
    /**
     * @param array $row
     *
     * @return User|null
     */
     
    public function model(array $row)
    {  
        /*
            $row[0] => student code
            $row[1] => course_id
            $row[2] => times 
        */
        try {
            
            $row[0] = str_replace(" ", "", $row[0]);
            if (!is_numeric($row[1]))
                return null;
                
            $user = User::where("code", $row[0])->first();
            
            if (!$user)
                return null;
            
            // check if the user code exist
            $countOfAssign = StudentCourse::where('course_id', $row[1])->where("student_id", $user->id)->count();
            
            if ($countOfAssign > 0) {
                return null;
            } else {
            
                $assign = StudentCourse::create([
                    "course_id" => $row[1],
                    "student_id" => $user->id,
                    "times" => $row[2]
                ]);
                  
                
                return $assign;
            }
        
        } catch (\Exception $ex) {
            
        }
        return null;
    }

    
}
