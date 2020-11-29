<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
//exit();

// remote post login
Route::get('/remote', 'Dashboard\StudentRemoteLoginController@login')->name('remoteLogin');


//********************************************
// dashboard routes
//********************************************
// check if user login
 
Route::group(["middleware" => "student"], function() {
    
    Route::get("students/", "dashboard\DashboardController@index");
    Route::get("/", "dashboard\DashboardController@index");
    
    // student routes
    Route::get("dashboard/myresearch", "dashboard\MyResearchController@index");
    Route::get("dashboard/myresearch/edit/{course}", "dashboard\MyResearchController@edit");
    Route::get("dashboard/myresearch/show/{course}", "dashboard\MyResearchController@show");
    Route::post("dashboard/myresearch/store/{course}", "dashboard\MyResearchController@store");

});
 
Route::group(["middleware" => "admin"], function() {

    Route::get("dashboard/", "dashboard\DashboardController@index");
    Route::get("dashboard/main", "dashboard\DashboardController@main");

    // department routes
    Route::get("dashboard/department", "dashboard\DepartmentController@index");
    Route::post("dashboard/department/store", "dashboard\DepartmentController@store");
    Route::get("dashboard/department/data", "dashboard\DepartmentController@getData");
    Route::get("dashboard/department/edit/{department}", "dashboard\DepartmentController@edit");
    Route::get("dashboard/department/remove/{department}", "dashboard\DepartmentController@destroy");
    Route::post("dashboard/department/update/{department}", "dashboard\DepartmentController@update");

    // level routes
    Route::get("dashboard/level", "dashboard\LevelController@index");
    Route::post("dashboard/level/store", "dashboard\LevelController@store");
    Route::get("dashboard/level/data", "dashboard\LevelController@getData");
    Route::get("dashboard/level/edit/{level}", "dashboard\LevelController@edit");
    Route::get("dashboard/level/remove/{level}", "dashboard\LevelController@destroy");
    Route::post("dashboard/level/update/{level}", "dashboard\LevelController@update");

    // studentgrade routes
    Route::get("dashboard/studentgrade/coursenotmatch", function(){ 
        return view('dashboard.studentgrade.coursenotmatch'); 
    });
    Route::get("dashboard/studentgrade", "dashboard\StudentGradeController@index");
    Route::get("dashboard/studentgrade/show", "dashboard\StudentGradeController@showForStudent");
    Route::post("dashboard/studentgrade/store", "dashboard\StudentGradeController@store");
    Route::get("dashboard/studentgrade/data", "dashboard\StudentGradeController@getData");
    Route::get("dashboard/studentgrade/edit/{studentgrade}", "dashboard\StudentGradeController@edit");
    Route::get("dashboard/studentgrade/remove/{studentgrade}", "dashboard\StudentGradeController@destroy");
    Route::post("dashboard/studentgrade/update/{studentgrade}", "dashboard\StudentGradeController@update");
    Route::post("dashboard/studentgrade/import", "dashboard\StudentGradeController@import");

    // doctor routes
    Route::get("dashboard/doctor", "dashboard\DoctorController@index");
    Route::post("dashboard/doctor/store", "dashboard\DoctorController@store");
    Route::get("dashboard/doctor/data", "dashboard\DoctorController@getData");
    Route::get("dashboard/doctor/edit/{doctor}", "dashboard\DoctorController@edit");
    Route::get("dashboard/doctor/remove/{doctor}", "dashboard\DoctorController@destroy");
    Route::post("dashboard/doctor/update/{doctor}", "dashboard\DoctorController@update");

    // admin routes
    Route::get("dashboard/admin", "dashboard\AdminController@index");
    Route::post("dashboard/admin/store", "dashboard\AdminController@store");
    Route::get("dashboard/admin/data", "dashboard\AdminController@getData");
    Route::get("dashboard/admin/edit/{admin}", "dashboard\AdminController@edit");
    Route::get("dashboard/admin/remove/{admin}", "dashboard\AdminController@destroy");
    Route::post("dashboard/admin/update/{admin}", "dashboard\AdminController@update");

    // student routes
    Route::get("dashboard/student", "dashboard\StudentController@index");
    Route::post("dashboard/student/store", "dashboard\StudentController@store");
    Route::get("dashboard/student/data", "dashboard\StudentController@getData");
    Route::get("dashboard/student/course/data", "dashboard\StudentController@getData2");
    Route::get("dashboard/student/edit/{student}", "dashboard\StudentController@edit");
    Route::get("dashboard/student/remove/{student}", "dashboard\StudentController@destroy");
    Route::post("dashboard/student/update/{student}", "dashboard\StudentController@update");
    Route::get("dashboard/student/show/{student}", "dashboard\StudentController@show");
    Route::post("dashboard/student/import", "dashboard\StudentController@import");

    // controlmanager routes
    Route::get("dashboard/controlmanager", "dashboard\ControlManagerController@index");
    Route::post("dashboard/controlmanager/store", "dashboard\ControlManagerController@store");
    Route::get("dashboard/controlmanager/data", "dashboard\ControlManagerController@getData");
    Route::get("dashboard/controlmanager/edit/{controlmanager}", "dashboard\ControlManagerController@edit");
    Route::get("dashboard/controlmanager/remove/{controlmanager}", "dashboard\ControlManagerController@destroy");
    Route::post("dashboard/controlmanager/update/{controlmanager}", "dashboard\ControlManagerController@update");

    // course routes
    Route::get("dashboard/course", "dashboard\CourseController@index");
    Route::post("dashboard/course/store", "dashboard\CourseController@store");
    Route::get("dashboard/course/data", "dashboard\CourseController@getData");
    Route::get("dashboard/course/edit/{course}", "dashboard\CourseController@edit");
    Route::get("dashboard/course/show/{course}", "dashboard\CourseController@show");
    Route::get("dashboard/course/assign/{course}", "dashboard\CourseController@assign");
    Route::post("dashboard/course/assign/update/{course}", "dashboard\CourseController@updateDoctors");
    Route::get("dashboard/course/remove/{course}", "dashboard\CourseController@destroy");
    Route::post("dashboard/course/update/{course}", "dashboard\CourseController@update");
   
    // studentcourse routes
    Route::get("dashboard/studentcourse", "dashboard\StudentCourseController@index");
    Route::post("dashboard/studentcourse/store", "dashboard\StudentCourseController@store");
    Route::get("dashboard/studentcourse/data", "dashboard\StudentCourseController@getData");
    Route::get("dashboard/studentcourse/edit/{studentcourse}", "dashboard\StudentCourseController@edit");
    Route::get("dashboard/studentcourse/assign/{course}", "dashboard\StudentCourseController@assign");
    Route::post("dashboard/studentcourse/assign/update/{course}", "dashboard\StudentCourseController@updateDoctors");
    Route::get("dashboard/studentcourse/remove/{studentcourse}", "dashboard\StudentCourseController@destroy");
    Route::post("dashboard/studentcourse/update/{studentcourse}", "dashboard\StudentCourseController@update");
    Route::post("dashboard/studentcourse/import", "dashboard\StudentCourseController@import");
  
    // research routes
    Route::get("dashboard/research", "dashboard\ResearchController@index");
    Route::post("dashboard/research/store", "dashboard\ResearchController@store"); 
    Route::get("dashboard/research/data", "dashboard\ResearchController@getData");
    Route::get("dashboard/research/edit/{research}", "dashboard\ResearchController@edit");
    Route::get("dashboard/research/remove/{research}", "dashboard\ResearchController@destroy");
    Route::post("dashboard/research/update/{research}", "dashboard\ResearchController@update");
    Route::post("dashboard/research/max-date/update", "dashboard\ResearchController@updateMaxDate");

    // studentresearch routes
    Route::get("dashboard/studentresearch", "dashboard\StudentResearchController@index"); 
    Route::post("dashboard/studentresearch/status/update/{studentresearch}", "dashboard\StudentResearchController@updateStatus");
    Route::get("dashboard/studentresearch/data", "dashboard\StudentResearchController@getData");
    Route::get("dashboard/studentresearch/edit/{studentresearch}", "dashboard\StudentResearchController@edit");
    Route::get("dashboard/studentresearch/remove/{studentresearch}", "dashboard\StudentResearchController@destroy");
    Route::post("dashboard/studentresearch/update/{studentresearch}", "dashboard\StudentResearchController@update");
    Route::post("dashboard/studentresearch/publish-result/{studentresearch}", "dashboard\StudentResearchController@publishResult");
    //
    
    // studentresearch routes
    Route::get("dashboard/studentresearch2", "dashboard\NewStudentResearchController@index");  
    Route::get("dashboard/studentresearch2/data", "dashboard\NewStudentResearchController@getData"); 
    //
    
    Route::post("dashboard/studentresearch/download", "dashboard\DownloadResearchController@download");
    //
    
    Route::get("dashboard/result/show", "dashboard\StudentResearchController@showResult");
    //
    Route::get("dashboard/studentresearch/admin-publish", "dashboard\StudentResearchController@adminPublish");
    Route::post("dashboard/studentresearch/publish-result", "dashboard\StudentResearchController@publishResultForAdmin"); 

    // notification routes
    Route::get("dashboard/notification", "dashboard\NotificationController@index");
    Route::get("dashboard/notification/data", "dashboard\NotificationController@getData");
    Route::get("dashboard/notification/remove/{notification}", "dashboard\NotificationController@destroy");

    // user routes
    Route::get("dashboard/user", "dashboard\UserController@index");
    Route::post("dashboard/user/store", "dashboard\UserController@store");
    Route::get("dashboard/user/data", "dashboard\UserController@getData");
    Route::get("dashboard/user/edit/{user}", "dashboard\UserController@edit");
    Route::get("dashboard/user/remove/{user}", "dashboard\UserController@destroy");
    Route::post("dashboard/user/update/{user}", "dashboard\UserController@update");

    // role routes
    Route::get("dashboard/role", "dashboard\RoleController@index");
    Route::post("dashboard/role/store", "dashboard\RoleController@store");
    Route::get("dashboard/role/data", "dashboard\RoleController@getData");
    Route::get("dashboard/role/edit/{role}", "dashboard\RoleController@edit");
    Route::get("dashboard/role/permission/{role}", "dashboard\RoleController@permissions");
    Route::post("dashboard/role/permission/update/{role}", "dashboard\RoleController@updatePermissions");
    Route::get("dashboard/role/remove/{role}", "dashboard\RoleController@destroy");
    Route::post("dashboard/role/update/{role}", "dashboard\RoleController@update");

    // problem routes
    Route::get("dashboard/student-problem", "dashboard\ComplainController@student");
    Route::get("dashboard/doctor-problem", "dashboard\ComplainController@doctor");
    Route::get("dashboard/student-problem/data", "dashboard\ComplainController@getDataStudent");
    Route::get("dashboard/doctor-problem/data", "dashboard\ComplainController@getDataDoctor");
    Route::post("dashboard/problem/update/{problem}", "dashboard\ComplainController@update");
    
    // profile routes
    Route::get("dashboard/profile", "dashboard\ProfileController@index");
    Route::post("dashboard/profile/update", "dashboard\ProfileController@update");
    Route::post("dashboard/profile/update-password", "dashboard\ProfileController@updatePassword");
    Route::post("dashboard/profile/update-phone", "dashboard\ProfileController@updatePhone");

    // option routes
    Route::get("dashboard/option/", "dashboard\SettingController@index");
    Route::get("dashboard/option/update", "dashboard\SettingController@update");
    Route::post("dashboard/option/update", "dashboard\SettingController@update");
    Route::post("dashboard/translation/update", "dashboard\SettingController@updateTranslation");

    // helper route
    Route::get("dashboard/print/{page}", "dashboard\HelperController@print");


    // report routes
    Route::get("dashboard/report/studentcourse", "dashboard\ReportController@studentCourses");
    Route::get("dashboard/report/studentresult", "dashboard\ReportController@studentResult");
    Route::get("dashboard/report/studentresult2", "dashboard\ReportController@studentResult2");
    Route::get("dashboard/report/course", "dashboard\ReportController@courses");
    Route::get("dashboard/report/student", "dashboard\ReportController@students");
    Route::get("dashboard/report/student-course-research", "dashboard\ReportController@studentCourseResearch");
    Route::get("dashboard/report/course-details", "dashboard\ReportController@courseDetails");
    Route::get("dashboard/report/course-details2", "dashboard\ReportController@courseDetails2");
    Route::get("dashboard/report/student-not-upload-research", "dashboard\ReportController@studentsNotUploadResearch");
    Route::get("dashboard/report/student-not-upload-research/data", "dashboard\ReportController@studentsNotUploadResearchData");
    Route::get("dashboard/report/course-result-count", "dashboard\ReportController@courseResultCount");
    Route::get("dashboard/report/course-result-count2", "dashboard\ReportController@courseResultCount2");
    Route::get("dashboard/report/one-student-result", "dashboard\ReportController@oneStudentResult");

});

// complaint
Route::post("dashboard/complain/store", "dashboard\ComplainController@store");

// auth route
//Route::get("students", "dashboard\LoginController@studentLogin");
Route::get("students/login", "dashboard\LoginController@studentLogin");
Route::get("dashboard/login", "dashboard\LoginController@index");
Route::post("dashboard/login", "dashboard\LoginController@login");
Route::post("dashboard/register", "dashboard\LoginController@register");
Route::post("dashboard/forget-password", "dashboard\LoginController@forgetPassword");
Route::post("dashboard/confirm-account", "dashboard\LoginController@confirmAccount");
Route::get("dashboard/logout", "dashboard\LoginController@logout");


Route::get("notify", "dashboard\NotificationController@get");

Route::get("test", function(Illuminate\Http\Request $request){
    $zip_file = 'file/researches2.zip'; // Name of our archive to download

    // Initializing PHP class
    $zip = new \ZipArchive();
    $zip->open($zip_file, \ZipArchive::CREATE/* | \ZipArchive::OVERWRITE*/);
    
    //dump($zip);
    //return;
    
    $file = public_path() . "/file/studentresearch/" . optional(App\StudentResearch::find(15))->file;
    $filename = "بحث_الطلاب_رقم_1" . ".pdf";
    
    
    // Adding file: second parameter is what will the path inside of the archive
    // So it will create another folder called "storage/" inside ZIP, and put the file there.
     
    // return $file;
    //return public_path('/file');
    $zip->addFile($file, $filename);
    $zip->close();
    
    // We return the file immediately after download
    echo "done";
    return response()->download(public_path('/') . "/" . $zip_file);
    
});

// show order
