<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Payment;
use Carbon\Carbon;

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


    //for admin
    public function store(Request $request){                

        if($request->method() != 'POST')
            return redirect()->back();    
            
        $setting = Setting::first();

        $appointmentCounts = Appointment::where('date', $request->input('date'))->count();

        if($appointmentCounts >= $setting->max_clients)
            return redirect()->back()->with('warning', 'Max Clients reached for this Date');

        if($setting->stop_appointments)
            return redirect()->back()->with('warning', 'Accepting Appointments is currently off, turn it on');

        $afterDate = \Carbon\Carbon::now()->yesterday();
        $beforeDate = \Carbon\Carbon::now()->addMonth();

        if(Appointment::where('pet_id', $request->input('pet_id'))->where('status', 0)->exists())
            return redirect()->back()->with('warning', 'This pet still have a pending appointment.');
              
        $validator = Validator::make($request->all(), [            
            'client_id' => 'required',
            'service_id' => 'required',
            'date' => 'required|date|before:' . $beforeDate . '|after:' . $afterDate,
        ],[
            'date.required' => 'Appointment Date is required.',
            'date.date' => 'Appointment Date is invalid.',
            'date.before' => 'Appointment Date must be before ' .  $beforeDate->isoFormat('MMM-DD-OY'),
            'date.after' => 'Appointment Date must be after ' .  $afterDate->isoFormat('MMM-DD-OY'),
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();  

        $appointment = new Appointment;        
        
        $appointment->pet_id = $request->input('pet_id');
        $appointment->service_id = $request->input('service_id');
        $appointment->date = $request->input('date');  
        
        $pet = Pet::find($request->input('pet_id'));

        $client = Client::find($pet->owner->id);

        $service = Service::find($request->input('service_id'));        

        $transaction = new Transaction;
        $transaction->pet_id = $request->input('pet_id');
        $transaction->type = 'Set Appointment';
        $transaction->has_payment = 1;        
        $transaction->approved_by = auth()->user()->id;   
        
        $transaction->save();

        $transaction->trans_id = Carbon::now()->isoFormat('YYYY') . '-' . sprintf("%06d", $transaction->id);
        $transaction->save();

        $payment = new Payment;
        $payment->amount = $setting->appointment_fee;
        $payment->transaction_id = $transaction->id;
        $payment->save();

        $appointment->transaction_id = $transaction->id;
        $appointment->save();

        return redirect('/admin')->with('success', 'Appointment with Client: ' . ucfirst($client->user->first_name) .' '. ucfirst($client->user->last_name) . ', of service: ' . ucfirst($service->desc) . ' for ' . ucfirst($pet->name) . ' has been added');

    }    

}
