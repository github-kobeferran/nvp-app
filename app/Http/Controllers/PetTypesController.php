<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PetType;
use App\Models\Pet;
use App\Models\Item;

class PetTypesController extends Controller
{

    public function store(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();       

        $validator = Validator::make($request->all(), [
            'type' => 'required|regex:/^[a-zA-Z]+$/u|max:50',    
        ]);    

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();                         
        }
        
        

        if(PetType::where('type', $request->input('type'))->exists())
            return redirect()->back()->with('error', 'There is already a ' . $request->input('type') .  ' type');
    
        $type = new PetType;

        $type->type = $request->input('type');
        $type->save();

        return redirect()->back()->with('success', ucfirst($type->type) . ' Added');

    }

    public function update(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();        

        $validator = Validator::make($request->all(), [
            'type' => 'required|regex:/^[a-zA-Z]+$/u|max:50',    
        ]);    

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();                         
        }

        if(PetType::where('type', $request->input('type'))->where('id', '!=', $request->input('id'))->exists())
            return redirect()->back()->with('error', 'There is already a ' . $request->input('type') .  ' type');

        $type = PetType::find($request->input('id'));        

        $type->type = $request->input('type');
        $type->save();

        return redirect()->back()->with('info', ucfirst($type->type) . ' Updated');

    }

    public function delete(Request $request){        

        if($request->method() != 'POST')
            return redirect()->back();        

        $type = PetType::find($request->input('id'));

        if(Pet::where('pet_type_id', $type->id)->exists())
            return redirect()->back()->with('warning', 'Deletion Failed. There are still pets registered with type ' . ucfirst($type->type));            

        if(Item::where('pet_type_id', $type->id)->exists())
            return redirect()->back()->with('warning', 'Deletion Failed. There are items registered with type ' . ucfirst($type->type));            

        $oldtype = $type->type;

        $type->delete();

        return redirect()->back()->with('info', 'Type ' . ucfirst($oldtype) . ' Deleted');

    }

    public function showData($id){

        return PetType::find($id)->toJson();

    }

    public function search($txt = null){

        if(is_null($txt)){

            return PetType::latest()->get()->toJson();                     

        } else {
            
            return PetType::query()
            ->where('type', 'LIKE', '%' . $txt . "%")                
            ->get()->toJson();                      

        }

    }

}
