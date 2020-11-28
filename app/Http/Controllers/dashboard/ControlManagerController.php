<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\helper\Message;
use App\helper\Helper;
use App\ControlManager;
use DB;
use DataTables;

class ControlManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("dashboard.controlmanager.index");
    }

    /**
     * return json data
     */
    public function getData() {
        return DataTables::eloquent(ControlManager::query())
                        ->addColumn('action', function(ControlManager $controlmanager) {
                            return view("dashboard.controlmanager.action", compact("controlmanager"));
                        })
                        ->addColumn('level_id', function(ControlManager $controlmanager) {
                            return optional($controlmanager->level)->name;
                        })
                        ->addColumn('doctor_id', function(ControlManager $controlmanager) {
                            return optional($controlmanager->doctor)->name;
                        })
                        ->rawColumns(['action'])
                        ->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $controlmanager = ControlManager::create($request->all());

            notify(__('add controlmanager'), __('add controlmanager') . " " . $controlmanager->name, 'fa fa-user-secret');

            return Message::success(Message::$DONE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ControlManager $controlmanager)
    {
        return $controlmanager->getViewBuilder()->loadEditView();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ControlManager $controlmanager)
    {
        try {
            $controlmanager->update($request->all());

            notify(__('edit controlmanager'), __('edit controlmanager') . " " . $controlmanager->name, "fa fa-user-secret");
            return Message::success(Message::$EDIT);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ControlManager $controlmanager)
    {
        if ($controlmanager->courses()->count() > 0)
            return Message::success(__("can't remove controlmanager has courses"));
        
        try {
            notify(__('remove controlmanager'), __('remove controlmanager') . " " . $controlmanager->name, "fa fa-user-secret");
            $controlmanager->delete();
            return Message::success(Message::$REMOVE);
        } catch (\Exception $ex) {
            return Message::error(Message::$ERROR);
        }
    }
}
