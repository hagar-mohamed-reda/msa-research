<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Problem;
use App\helper\Message;
use App\helper\Helper; 
use DB;
use DataTables;
use App\Department;
use App\Level; 
use App\User;
use App\Course;
use App\StudentResearch; 

class DownloadResearchController extends Controller
{
    
    /**
     * filter research to download
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        $query = StudentResearch::query();
        $search = false;
        
        if ($request->level_id > 0 && !$request->department_id && $request->department_id != null) {
            $studentIds = DB::table('users')
            ->where('type', 'student')
            ->where('graduated', '0')
            ->where('level_id', $request->level_id)
            ->pluck('id')
            ->toArray();
            
            $query->whereIn('student_id', $studentIds);
            $search = true;
        }
        
        if ($request->department_id > 0) {
            $studentIds = DB::table('users')
            ->where('type', 'student')
            ->where('graduated', '0')
            ->where('department_id', $request->department_id)
            ->where('level_id', $request->level_id)
            ->pluck('id')
            ->toArray();
            
            $query->whereIn('student_id', $studentIds);
            $search = true;
        }
        
        if ($request->course_id > 0) {
            $query->where('course_id', $request->course_id);
            $search = true;
        } 
         
        $researches = $search? $query->get() : [];
         
        /*if ($query->count() > 200) {
            return Message::error(__('cant download more than 200 research'));
        }*/
        
        // create zip file
        $fileUrl = $this->createZipFile($request, $researches);
        
        $response = [
            "research_count" => $query->count(),
            "url" => $fileUrl 
        ];
        
        return Message::success(Message::$DONE, $response); 
    }
    
    /**
     * create filename of the zip file
     * 
     * @param $request Request
     */
    public function createZipFileName(Request $request) {
        $filename = "ابحاث_الطلاب_";
        
        if ($request->course_id > 0) {
            $filename .= "_فى_مادة_" . optional(Course::find($request->course_id))->name;
        }
        
        if ($request->level_id > 0) {
            $filename .= "_المستوى_" . optional(Level::find($request->level_id))->name;
        }
        
        if ($request->department_id > 0) {
            $filename .= "_الشعبه_" . optional(Department::find($request->department_id))->name;
        }
        
        // add time to zip file
        $filename .= "_" . date('Y-m-d H:i:s'); 
        
        // add zip extension
        $filename .= ".zip";
        
        // trim all spaces
        $filename = str_replace(" ", "_", $filename);
        
        return $filename;
    }
    
    
    /**
     * create filename of student research
     * 
     * @param $research StudentResearch research of the student
     */
    public function createResearchFileName(StudentResearch $research) {
        $filename = "بحث_الطالب_";
        
        // add name to file
        $filename .= "(" . optional($research->student)->name . ")";
        
        // add course name
        $filename .= "(المقرر:" . optional($research->course)->name . ")";
         
        // add research name
        $filename .= "(البحث:" . optional($research->research)->title . ")";
          
        // add level to file
        $filename .= "(" . optional(optional($research->student)->level)->name . ")";

        // add department to file
        $filename .= "(الشعبه:" . optional(optional($research->student)->department)->name . ")";
        
        // add pdf extension
        $filename .= ".pdf";
        
        // trim all spaces
        $filename = str_replace(" ", "_", $filename);
        
        return $filename;
    }
    
    /**
     * remove old file if exist 
     * 
     * @param $filename 
     */
    public function removeOldZipFile($filename) {
        $path = public_path($filename);
        
        if (file_exists($path)) {
            unlink($path);
        }
    }
    
    /**
     * create zip file
     * 
     * @param Request $request
     */
    public function createZipFile(Request $request, $researches) { 
        // Name of our archive to download
        $zip_file = "file/" . $this->createZipFileName($request); 
        
        // remove old file if exist
        $this->removeOldZipFile($zip_file);
    
        // Initializing PHP class
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE/* | \ZipArchive::OVERWRITE*/);
         
        // Adding file: second parameter is what will the path inside of the archive
        // So it will create another folder called "storage/" inside ZIP, and put the file there.
        foreach($researches as $research) {
            $researchFile = public_path() . "/file/studentresearch/" . $research->file;
            $filename = $this->createResearchFileName($research);
         
            //if ($research->file) {
                $zip->addFile($researchFile, $filename);
            //}
        }
        
        $zip->close();
        
        return url($zip_file);
        // We return the file immediately after download  
        //return response()->download(public_path('/') . "/" . $zip_file);
    }

    
    
}
