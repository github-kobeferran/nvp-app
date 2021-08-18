<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\Pet;

class AppointmentsController extends Controller
{
    
    public function done(Request $request){             

        if($request->method() != 'POST')
            return redirect()->back();

        $appointment = Appointment::find($request->input('id'));
        $appointment->done = 1;

        $pet = Pet::find($request->input('pet_id'));

        if($pet->checked == 0)
            $pet->checked = 1;
        
        $pet->save();        
        $appointment->save();

        return redirect('/admin');

    }


    public function store(Request $request){  

        if($request->method() != 'POST')
            return redirect()->back();        

        $afterDate = \Carbon\Carbon::now()->yesterday();
        $beforeDate = \Carbon\Carbon::now()->addMonth();
        $start_time_formatted = \Carbon\Carbon::parse($request->input('start_time'))->format('Y-m-d H:i:s');
        $end_time_formatted = \Carbon\Carbon::parse($request->input('end_time'))->format('Y-m-d H:i:s');

        if( Appointment::where('client_id', $request->input('client_id'))
                        ->where('service_id', $request->input('service_id'))
                        ->where('pet_id', $request->input('pet_id'))
                        ->where('start_time', $start_time_formatted) 
                        ->where('end_time', $end_time_formatted) 
                        ->where('done', 0)                         
                        ->exists()
          ){
            return redirect()->back()->with('error', 'Duplicate appointment details, can\'t do.');
          }
      
        $validator = Validator::make($request->all(), [            
            'client_id' => 'required',         
            'service_id' => 'required',         
            'start_time' => 'required|date_format:m/d/Y g:i A|before:' . $beforeDate . '|after: ' . $afterDate,         
            'end_time' => 'required|date_format:m/d/Y g:i A|before:' . $beforeDate . '|after:start_time',         
           
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();  

        $appointment = new Appointment;        

        $appointment->client_id = $request->input('client_id');
        $appointment->service_id = $request->input('service_id');
        $appointment->start_time = $start_time_formatted;
        $appointment->end_time = $end_time_formatted;
        $appointment->pet_id = $request->input('pet_id');
        

        $client = Client::find($appointment->client_id);
        $service =Service::find($appointment->service_id);
        $pet =Pet::find($appointment->pet_id);
        
        $appointment->save();

        return redirect('/admin')->with('success', 'Appointment with Client: ' . ucfirst($client->user->name) . ', of service: ' . ucfirst($service->desc) . ' for ' . ucfirst($pet->name) . ' has been added');

    }    

}
