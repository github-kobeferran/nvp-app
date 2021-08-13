<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Pet;
use App\Models\User;
use App\Models\Client;
use Carbon\Carbon;

class PetsController extends Controller
{
    public function create($email){

        if(User::where('email', $email)->exists()){    

            return view('pet.create')->with('user', User::where('email', $email)->first());

        } else {
            
            if(auth()->user()->isAdmin())
                return redirect('/admin');
            else    
                return redirect('/user');

        }        

    }

    public function store(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();

        $client = Client::find($request->input('client_id'));                 

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',         
            'breed' => 'required|regex:/^[a-z ,.\w\'-]*$/|max:50', 
            'dob' => 'required|date|before:' . Carbon::now()->addDay()->toDateTimeString() . '|after:' . Carbon::now()->subYears(30),
            'weight' => 'required|min:.01|max:100',
            'height' => 'required|min:1|max:112',
            'color' => 'required|min:1|max:50',
        ]);    

        if ($validator->fails()) {
            return redirect('/createpet/'. $client->user->email)
                            ->withErrors($validator)
                            ->withInput();                         
        }

        if(Pet::where('client_id', $client->id)->where('name', $request->input('name'))->exists())
            return redirect()->back()->with('warning', 'Pet Registration failed. There is already a pet named ' . ucfirst($request->input('name')) . ' owned by ' . $client->user->name);

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
            $path = $request->file('image')->storeAs('public/images/pet/', $fileNameToStore);            
        }

        $pet = new Pet;

        $pet->name = $request->input('name');
        $pet->dob = $request->input('dob');
        $pet->breed = $request->input('breed');        
        $pet->color = $request->input('color');        

        if(auth()->user()->isAdmin())
            $pet->checked = $request->input('checked');
        else
            $pet->checked = 0;

        $pet->client_id = $client->id;
        $pet->pet_type_id = $request->input('type_id');
        $pet->sex = $request->input('sex');
        $pet->weight = $request->input('weight');
        $pet->height = $request->input('height');

        if($request->hasFile('image')){
            $pet->image = $fileNameToStore;
        }
        
        $pet->save();

        return redirect('/createpet/'. $client->user->email)->with('success', 'Pet ' . $pet->name . ', is registered to owner ' . $client->user->name);

    }

    public function view(){

        return view('admin.view.pet');

    }

    public function edit($name){

        $pets = Pet::where('name', $name)->get();    
        
        $the_pet = null;

        foreach($pets as $pet){

            if($pet->client_id == auth()->user()->client->id)
                $the_pet = $pet;

        }

        if(is_null($the_pet))
            return redirect()->back();
        else
            return view('pet.edit')->with('pet', $the_pet);

    }

    public function update(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();

        $client = Client::find(auth()->user()->client->id);                 

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-z ,.\w\'-]*$/|max:100',         
            'breed' => 'required|regex:/^[a-z ,.\w\'-]*$/|max:50', 
            'dob' => 'required|date|before:' . Carbon::now()->addDay()->toDateTimeString() . '|after:' . Carbon::now()->subYears(30),
            'weight' => 'required|min:.01|max:100',
            'height' => 'required|min:1|max:112',
            'color' => 'required|min:1|max:50',
        ]); 
        
        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();                         
        }

        if(Pet::where('client_id', $client->id)->where('name', $request->input('name'))->where('id', '!=', $request->input('id'))->exists())
            return redirect()->back()->with('warning', 'Pet Update failed. There is already a pet named ' . ucfirst($request->input('name')) . ' owned by ' . $client->user->name);

        
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
            $path = $request->file('image')->storeAs('public/images/pet/', $fileNameToStore);            
        }

        $pet = Pet::find($request->input('id'));

        $oldname = $pet->name;

        $pet->name = $request->input('name');
        $pet->dob = $request->input('dob');
        $pet->breed = $request->input('breed');        
        $pet->color = $request->input('color');                     
        $pet->pet_type_id = $request->input('type_id');
        $pet->sex = $request->input('sex');
        $pet->weight = $request->input('weight');
        $pet->height = $request->input('height');

        if($request->hasFile('image')){

            if(!empty($client->image))
                Storage::disk('public')->delete('images/pet/' . $pet->image);

            $pet->image = $fileNameToStore;
        }
        
        $pet->save();

        return redirect('/editpet/'. $pet->name)->with('success', 'Pet ' . ucfirst($oldname) . ', is successfully updated.');

    }

    public function show($email, $pet = null){

        if(!auth()->user()->isAdmin()){
            if(auth()->user()->email != $email)
                return redirect('user/'. $email);
        }

        if(is_null($pet)){            

            return redirect('user/' . $email);

        } else {


            $user = User::where('email', $email)->first();

            if(Pet::where('client_id', $user->client->id)->where('name', $pet)->doesntExist()){
                               
                if(auth()->user()->isAdmin()){
                    return redirect('/admin');    
                } else {
                    return redirect('/user');  
                }

            }

            $pet = Pet::where('client_id', $user->client->id)
                        ->where('name', $pet)
                        ->first();
            
            $client = $user->client;

            $pet->type;
            $pet->dob_string = $pet->dob;            
            $pet->age = $pet->dob;            

            return view('pet.show')
                 ->with('client', $client)
                 ->with('user', $user)
                 ->with('pet', $pet);

        }

    }

    public function search($text = null){

        if(is_null($text)){

            $pets = Pet::latest();

            foreach($pets as $pet){

                $pet->type;
                $pet->owner;
                $pet->owner->user;
                $pet->dob_string = $pet->dob;
                $pet->age = $pet->dob;

            }

            return $pets->toJson();

        } else {
            
            $pets = Pet::query()
            ->where('name', 'LIKE', '%' . $text . "%")
            ->orWhere('breed', 'LIKE', '%' . $text . "%")          
            ->get();

            foreach($pets as $pet){
                $pet->type;
                $pet->owner;
                $pet->owner->user;
                $pet->dob_string = $pet->dob;
                $pet->age = $pet->dob;
            }

            return $pets->toJson();

        }

    }

   
    

}
