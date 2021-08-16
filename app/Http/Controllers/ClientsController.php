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
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',                                                          
            'email' => 'required:email',            
        ]);

        if ($validator->fails()) {
            return redirect()->route('client.view')
                            ->withErrors($validator)
                            ->withInput();                         
        }

        $name = $request->input('name');
        $pass = Client::generateRandomString();

        $user = User::create([
            'name' => $name ,
            'email' => $request->input('email'),
            'password' => Hash::make($pass),
        ]);     

        Client::create([
            'user_id' => $user->id,            
        ]);  

        Mail::to($user)->send(new ClientCreated($name, $pass));

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
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',                                                          
            'dob' => 'date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'contact' => 'digits_between:10,15',
            'address' => 'max:100',
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

     
        
        $user->name = $request->input('name');                

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

    public function search($text = null){

        if(is_null($text)){

            $users = User::where('user_type', 0)->get();       

            foreach($users as $user){
                $user->client;
                $user->client->dob_string = $user->client->dob;
                $user->client->age = $user->client->dob;
            }

            return $users->toJson();

        } else {

            $users = User::query()
            ->where('name', 'LIKE', '%' . $text . "%")
            ->orWhere('email', 'LIKE', '%' . $text . "%")          
            ->get();

            foreach($users as $user){
                $user->client;
                $user->client->dob_string = $user->client->dob;
                $user->client->age = $user->client->dob;
            }

            return $users->toJson();

        }

    }   
    
    public function export() 
    {
        return Excel::download(new ClientsExport, 'clients ' .  \Carbon\Carbon::now()->isoFormat('OY-MMM-DD') .  '.xlsx');
    }
   

}
