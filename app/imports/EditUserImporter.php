<?php

namespace App\imports; 

use Maatwebsite\Excel\Concerns\ToModel; 
use App\User;

class EditUserImporter  implements ToModel
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
            $row[1] => name
            $row[2] => set_number
            $row[3] => level_id
            $row[4] => department_id 
            $row[5] => national_id 
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

            if ($user->active == 1)
                return null;
            
             
                $user->update([
                    "set_number" => $row[2],
                    "level_id" => $row[3],
                    "department_id" => $row[4],
                    "national_id" => $row[5],
                ]);
                
                return $user;
           
            
        } catch (\Exception $ex) {
            
        }
        return null;
    }

    
}
