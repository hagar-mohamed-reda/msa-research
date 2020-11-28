<?php

namespace App\imports; 

use Maatwebsite\Excel\Concerns\ToModel; 
use App\User;
use App\StudentGrade;
use App\Course;
use DB;

class StudentGradeImporter  implements ToModel
{
    
    /**
     * @param array $row
     *
     * @return User|null
     */ 
    public function model(array $row)
    {  
        /*
            $row[0] => code 
            $row[3] => course name 
            $row[4] => grade 
            $row[5] => gpa 
        */
        try {
            $row[0] = str_replace(" ", "", $row[0]);
            if (!is_numeric($row[0]))
                return null;
            
            // check if the user code exist
            $user = User::where('code', $row[0])->first();
            
            if (!$user) {
                return null;
            }
            
            $courseName = $row[3];
            
            $course = Course::where('name', 'like', '%'.$courseName.'%')->first();
            
            if (!$course) { 
                DB::table('test')->insert([
                    ['name' => $row[3]]
                ]);
                
                return null;
            }
            
            //return null;
            
            $grade = $row[4];
            $gpa = $row[5];
 
            // remove old
            StudentGrade::where('course_id', $course->id)->where('student_id', $user->id)->delete();
             
             
            $studentgrade = StudentGrade::create([
                "student_id" => $user->id,
                "course_id" => $course->id,
                "grade" => $grade,
                "gpa" => $gpa
            ]);
                
            return $studentgrade;
           
            
        } catch (\Exception $ex) {
             
        }
        return null;
    }

    
}
