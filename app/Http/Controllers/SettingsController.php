<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Setting;

class SettingsController extends Controller
{
    

    public function update(Request $request){        

        if($request->method() != 'POST')
             return redirect()->back();

        $setting = Setting::first();

        $setting->max_clients = $request->input('max_clients');
        
        if($request->has('stop_appointments'))
            $setting->stop_appointments = 1;
        else
            $setting->stop_appointments = 0;

        if($request->has('stop_orders'))
            $setting->stop_orders = 1;
        else
            $setting->stop_orders = 0;

        $setting->appointment_fee = $request->input('appointment_fee');
        $setting->weeks = $request->input('weeks');

        $setting->save();        

        return redirect('/admin')->with('primary', 'Settings Updated');
    }

}
