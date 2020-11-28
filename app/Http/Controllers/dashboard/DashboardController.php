<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Category;
use App\User; 
use DB;

class DashboardController extends Controller
{
    
    /**
     * return index file of dashboard
     * 
     * @return object
     */
    public function index() {
        $this->checkIfLogin();
        
        return view("dashboard.index");
    }

    /**
     * check in cookie if the user login before
     */
    public function checkIfLogin() {
        if (isset($_COOKIE["user"])) {
            session(["user" => $_COOKIE["user"]]);
        }
    }

    
    /**
     * main page in dashboard
     * @return type
     */
    public function main() { 
        return view("dashboard.main");
    }
}
