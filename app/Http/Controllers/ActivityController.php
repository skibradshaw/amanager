<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    //
    public function index()
    {
    	$activity = Activity::with('causer')->take('100')->orderBy('created_at','desc')->get();

    	return view('admin.activity',[
    		'activity' => $activity
    	]);
    }
}
