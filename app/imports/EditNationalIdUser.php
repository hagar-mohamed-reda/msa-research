<?php

namespace App\imports; 

use Maatwebsite\Excel\Concerns\ToModel; 
use App\User;

class EditNationalIdUser  implements ToModel
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
            $row[1] => national_id 
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
            
            else {
                $user->update([
                    "national_id" => $row[1] 
                ]);
                
                return $user;
            }
            
        } catch (\Exception $ex) {
            
        }
        return null;
    }

    
}
