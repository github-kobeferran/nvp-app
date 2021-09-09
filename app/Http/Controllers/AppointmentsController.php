<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\AppointmentServices;
use Carbon\Carbon;
use App\Mail\AppointmentSet;
use App\Mail\AppointmentReschedule;


class AppointmentsController extends Controller
{

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

        $afterDate = \Carbon\Carbon::yesterday();
        $beforeDate = \Carbon\Carbon::now()->addMonth();

        if(Appointment::where('pet_id', $request->input('pet_id'))->where('status', 0)->exists())
            return redirect()->back()->with('warning', 'This pet still have a pending appointment.');
              
        $validator = Validator::make($request->all(), [            
            'client_id' => 'required',
            'services' => 'required',
            'date' => 'required|date|before:' . $beforeDate . '|after:' . $afterDate,
        ],[
            'date.required' => 'Appointment Date is required.',
            'date.date' => 'Appointment Date is invalid.',
            'date.before' => 'Appointment Date must be before ' .  $beforeDate->isoFormat('MMMM DD, OY'),
            'date.after' => 'Appointment Date must be after ' .  $afterDate->isoFormat('MMMM DD, OY'),
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator);  

        $pet = Pet::find($request->input('pet_id'));
        $client = Client::find($pet->owner->id);      

        $appointment = new Appointment;        
        
        $appointment->pet_id = $request->input('pet_id');                

        $appointment->date = $request->input('date');  

        $transaction = new Transaction;
        $transaction->client_id = $client->id;
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

        $services = $request->input('services');                    
        $collect = collect();

        $services_message = [];
        $totalfee = 0;

        foreach($services as $id){

            $collect->push(Service::find($id));
            array_push($services_message, Service::find($id)->desc);            
            $totalfee+= Service::find($id)->price;
        }  

        foreach($collect as $service){
            
            $appointment_service = new AppointmentServices;            
            $appointment_service->appointment_id = $appointment->id;
            $appointment_service->service_id = $service->id;

            $appointment_service->save();
            
        }                                
        
        $list = implode(', ', $services_message);

        Mail::to($client->user)->send(new AppointmentSet($client->user->first_name. ' ' . $client->user->last_name, $pet->name, Carbon::parse($appointment->date)->isoFormat('MMMM Do, OY'), $totalfee, $list));

        return redirect('/admin')->with('success', 'Appointment with Client: ' . ucfirst($client->user->first_name) .' '. ucfirst($client->user->last_name) . ', of services: [' . $list  . '] for ' . ucfirst($pet->name) . ' has been added');

    }    

     
    public function done(Request $request){             

        if($request->method() != 'POST')
            return redirect()->back();

        $appointment = Appointment::find($request->input('id'));
        $appointment->status = 1;

        $pet = Pet::find($request->input('pet_id'));

        if($pet->checked == 0)
            $pet->checked = 1;
        
        $pet->save();        
        $appointment->save();

        return redirect('/admin');

    }

    public function abandon(Request $request){             

        if($request->method() != 'POST')
            return redirect()->back();

        $appointment = Appointment::find($request->input('id'));
        $appointment->status = 2;
               
        $appointment->save();

        return redirect('/admin');

    }

    public function updateSchedule(Request $request){             

        if($request->method() != 'POST')
            return redirect()->back();

        $setting = Setting::first();

        $afterDate = \Carbon\Carbon::yesterday();
        $beforeDate = \Carbon\Carbon::now()->addMonth();

        $appointmentCounts = Appointment::where('date', $request->input('date'))->count();

        if($setting->stop_appointments){

            if(auth()->user()->isAdmin())
                return redirect()->back()->with('warning', 'Accepting Appointments is currently off, turn it on');
            else
                return redirect()->back()->with('warning', 'Sorry, accepting Appointments is currently off');


        }

        if($appointmentCounts >= $setting->max_clients)
            return redirect()->back()->with('warning', 'Max Clients reached for this Date');

        $validator = Validator::make($request->all(), [            
            'date' => 'required|date|before:' . $beforeDate . '|after:' . $afterDate,
        ],[
            'date.required' => 'Appointment Date is required.',
            'date.date' => 'Appointment Date is invalid.',
            'date.before' => 'Appointment Date must be before ' .  $beforeDate->isoFormat('MMMM DD, OY'),
            'date.after' => 'Appointment Date must be after ' .  $afterDate->isoFormat('MMMM DD, OY'),
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator);
        

        $appointment = Appointment::find($request->input('id'));
        $client = Client::find($request->input('client_id'));

        $olddate = Carbon::parse($appointment->date)->isoFormat('MMMM Do, OY');

        if($appointment->date == $request->input('date'))
            return redirect()->back()->with('info', 'Can\'t reschedule to the same date.');

        $appointment->date = $request->input('date');
               
        $appointment->save();        

        $transaction = new Transaction;
        $transaction->client_id = $appointment->pet->owner->id;
        $transaction->type = 'Reschedule Appointment';
        $transaction->has_payment = 0;        

        if(auth()->user()->isAdmin())
            $transaction->approved_by = auth()->user()->id;
        
        
        $transaction->save();

        $transaction->trans_id = Carbon::now()->isoFormat('YYYY') . '-' . sprintf("%06d", $transaction->id);
        $transaction->save();

        $newdate = Carbon::parse($appointment->date)->isoFormat('MMMM Do, OY');

        $services = [];
        $totalFee = 0;

        foreach($appointment->services() as $service){

            array_push($services, Service::find($service->id)->desc);
            $totalFee+= $service->price;

        }

        $list = implode(', ', $services);

        Mail::to($client->user)->send(new AppointmentReschedule($client->user->first_name. ' ' . $client->user->last_name, $appointment->pet->name, $olddate, $newdate, $totalFee, $list));

        if(auth()->user()->isAdmin())
            return redirect('/admin')->with('info', 'Appointment Schedule updated');
        else
            return redirect('/pet/'. $client->user->email . '/'. $appointment->pet->name)->with('info', 'Appointment Schedule of '. $appointment->pet->name . ' updated');

    }

}
