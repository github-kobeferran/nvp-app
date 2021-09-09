<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;

class ServicesController extends Controller
{
    
    public function view(){

        return view('admin.view.services');

    }

    public function store(Request $request){        

        if($request->method() != 'POST')
            return redirect()->back(); 

        if(Service::where('desc', '=', $request->input('desc'))->exists())
            return redirect()->back()->with('warning', 'Submission Failed. There is already an existing service named '. $request->input('desc'));

        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/[A-Za-z0-9]+/|max:100',                     
            'price' => 'required|gte:50|lte:50000',                     
        ]);    

        $service = new Service;        

        $service->desc = $request->input('desc');     
        $service->price = $request->input('price');     
        
        $service->save();

        return redirect('admin/services')->with('success', 'Service '. ucfirst($service->desc) . ' has been successfully Added');    

    }

    public function update(Request $request){        

        if($request->method() != 'POST')
            return redirect()->back(); 

        if(Service::where('desc', '=', $request->input('desc'))->where('id', '!=', $request->input('id'))->exists())
            return redirect()->back()->with('warning', 'Updation Failed. There is already an existing service named '. $request->input('desc'));

        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/[A-Za-z0-9]+/|max:100',                     
            'price' => 'required|gte:50|lte:50000',                     
        ]);    

        $service = Service::find($request->input('id'));        

        $service->desc = $request->input('desc');     
        $service->price = $request->input('price');     
        
        $service->save();

        return redirect('admin/services')->with('info', 'Service '. ucfirst($service->desc) . ' has been successfully Updated');    

    }

    public function delete(Request $request){

        if($request->method() != 'POST')
            return redirect()->back(); 

        //add validation here if service is being used in appointments and schedules        

        $service = Service::find($request->input('id'));

        $service->delete();

        return redirect('admin/services')->with('info', 'Service '. ucfirst($service->desc) . ' has been deleted.');    
        

    }

    public function totalFee($ids){

        $totalfee = 0;
        $idsArray = str_split($ids);

        foreach($idsArray as $id){
            $totalfee+= Service::find($id)->price;
        }

        return $totalfee;

    }

}
