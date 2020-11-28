<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelperController extends Controller
{
    /**
     * print any report
     * load report view in report print template
     */
    public function printReport($reportName) {
        return view("layout.print", compact("reportName"));
    }
}
