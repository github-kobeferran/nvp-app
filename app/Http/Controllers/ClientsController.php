<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Client;
use App\Models\User;
use App\Models\Pet;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Mail\ClientCreated;
use App\Exports\ClientsExport;


class ClientsController extends Controller
{
    
    public function create(){
        
    }

    public function store(Request $request){

        if($request->method() != "POST")
            return redirect()->back();
    
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',                                                          
            'middle_name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',                                                          
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',                                                          
            'email' => 'required:email',            
        ]);

        if ($validator->fails()) {
            return redirect()->route('client.view')
                            ->withErrors($validator)
                            ->withInput();                         
        }

        $last_name = $request->input('last_name');
        $middle_name = $request->input('middle_name');
        $first_name = $request->input('first_name');
        $pass = Client::generateRandomString();

        $user = User::create([
            'last_name' =>  $last_name,
            'middle_name' =>  $middle_name,
            'first_name' =>  $first_name,
            'email' => $request->input('email'),
            'password' => Hash::make($pass),
        ]);     

        Client::create([
            'user_id' => $user->id,            
        ]);  

        Mail::to($user)->send(new ClientCreated($first_name . ' ' . $last_name, $pass));

        return redirect()->route('client.view')->with('success', 'Client Added.');

    }

    public function edit(){        

        $user = auth()->user();

        $client = $user->client;        

        return view('client.edit')->with('client', $client)->with('user', $user);

    }

    public function update(Request $request){

        $before_date = Carbon::now()->subYears(13); 
        $after_date = new Carbon('1941-01-01'); 

        if($request->method() != 'POST')
            return redirect()->back();

        $client = Client::find($request->input('id'));

        $user = User::find($client->user_id);
        

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',                                                          
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',                                                          
            'middle_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',                                                          
            'dob' => 'date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'contact' => 'digits_between:10,15',
            'address' => 'max:255',
        ],[
            'dob.required' => 'Date of Birth is required',
            'dob.before' => 'Date of Birth must be before '. $before_date->isoFormat('MMM DD, OY'),
            'dob.after' => 'Date of Birth must be after '. $after_date->isoFormat('MMM DD, OY'),
        ]);

        if ($validator->fails()) {
            return redirect()->route('client.edit')
                         ->withErrors($validator)
                         ->withInput();                         
        }
     
        if($request->hasFile('image')){
            $validator = Validator::make($request->all(), [
                'image' => 'image|max:10000',  
            ]);

            if ($validator->fails()) {
                return redirect()->route('client.edit')
                             ->withErrors($validator)
                             ->withInput();                         
            }

            $filenameWithExt = $request->file('image')->getClientOriginalName();        
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);        
            $extension = $request->file('image')->getClientOriginalExtension();        
            $fileNameToStore = $filename.'_'.time().'.'.$extension;        
            $path = $request->file('image')->storeAs('public/images/client/', $fileNameToStore);            
        }

     
        
        $user->last_name = $request->input('last_name');                
        $user->first_name = $request->input('first_name');                
        $user->middle_name = $request->input('middle_name');                

        if($request->hasFile('image')){

            if(!empty($client->image))
                Storage::disk('public')->delete('images/client/' . $client->image);

            $client->image = $fileNameToStore;            

        }
        
        $client->dob = $request->input('dob');
        $client->sex = $request->input('sex');
        $client->contact = $request->input('contact');
        $client->address = $request->input('address');

        $user->save();
        $client->save();

        return redirect()->route('client.edit')->with('success', 'Personal Information is Updated');

    }

    public function view(){

        return view('admin.view.client');

    }
  
    public function export() 
    {
        return Excel::download(new ClientsExport, 'clients ' .  Carbon::now()->isoFormat('OY-MMM-DD') .  '.xlsx');
    }
   
    public function getRemainingPets($id){

        $client = Client::find($id);

        $pets = $client->pet;

        $valid = collect(new Pet);

        foreach($pets as $pet){

            if(Appointment::where('status', 0)->where('pet_id',  $pet->id)->doesntExist()){
                $valid->push($pet);
            }

        }

        $filtered = $valid->filter(function ($value, $key) {
            return $value != null;
        });

        return $filtered->all();

    }

    public function viewTransactions($email){

        if(is_null($email)){
            if(auth()->user()->isAdmin())
                return redirect('admin');
            else
                return view('client.transactions')->with('the_client', User::where('email', auth()->user()->email)->first()->client);            
        }else {
            return view('client.transactions')->with('the_client',  User::where('email', auth()->user()->email)->first()->client);            
        }


    }

}
