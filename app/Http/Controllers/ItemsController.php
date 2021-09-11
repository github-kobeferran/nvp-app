<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Client;
use App\Models\Transaction;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;


class ItemsController extends Controller
{
    
    public function view(){

        return view('admin.view.item');

    }

    public function store(Request $request){  
        
        if($request->method() != 'POST')
            return redirect()->back();     

        if(Item::where('desc', '=', $request->input('desc'))->exists())
            return redirect()->back()->with('warning', 'Submission Failed. There is already an existing item named '. $request->input('desc'));

        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/[A-Za-z0-9]+/|max:100',         
            'category_id' => 'required',
            
            'quantity' => 'required|min:1|max:10000',
            'deal_price' => 'required|gte:5|lte:100000',
            'reg_price' => 'required|gte:5|lte:100000',
            'note' => 'max:255',
        ],[
            'desc.regex' => 'Item Description is invalid',
            'category_id.required' => 'Item Category is required',
            'deal_price.gte' => 'Dealers Price must be greater than or equal to 5',
            'deal_price.lte' => 'Dealers Price must be less than or equal to 100000',
            'reg_price.gte' => 'Regular Price must be greater than or equal to 5',
            'reg_price.lte' => 'Regular Price must be less than or equal to 100000',
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput(); 
            
        if($request->hasFile('image')){
            $validator = Validator::make($request->all(), [
                'image' => 'image|max:5000',  
            ]);

            if ($validator->fails()) {
                return redirect()->route('item.view')->withErrors($validator)->withInput();                         
            }

            $filenameWithExt = $request->file('image')->getClientOriginalName();        
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);        
            $extension = $request->file('image')->getClientOriginalExtension();        
            $fileNameToStore = $filename.'_'.time().'.'.$extension;        
            $path = $request->file('image')->storeAs('public/images/item/', $fileNameToStore);            
        }

        $item = new Item;

        $item->desc = $request->input('desc');
        $item->item_category_id = $request->input('category_id');

        if($request->input('all_types') == 1)
            $item->pet_type_id = null;            
        else 
            $item->pet_type_id = $request->input('type_id');

        if($request->hasFile('image'))
            $item->image = $fileNameToStore;                    

        $item->quantity = $request->input('quantity');
        $item->deal_price = $request->input('deal_price');
        $item->reg_price = $request->input('reg_price');

        $item->save();

        return redirect('admin/inventory')->with('success', 'Item Added');                 

    }

    public function delete(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();   
        
        $item = Item::find($request->input('id'));

        $oldname = $item->desc;

        $item->delete();

        return redirect('admin/inventory')->with('info', 'Item '. ucfirst($oldname) .' Deleted');                 

    }

    public function update(Request $request){                  

        if($request->method() != 'POST')
            return redirect()->back();

        if(Item::where('desc', '=', $request->input('desc'))->where('id', '!=', $request->input('id'))->exists())
            return redirect()->back()->with('warning', 'Update Failed. There is already an existing item named '. $request->input('desc'));

        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/[A-Za-z0-9]+/|max:100',         
            'quantity' => 'required|min:1|max:10000',
            'deal_price' => 'required|gte:5|lte:100000',
            'reg_price' => 'required|gte:5|lte:100000',
            'note' => 'max:255',
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();  

        if($request->hasFile('image')){
            $validator = Validator::make($request->all(), [
                'image' => 'image|max:5000',  
            ]);

            if ($validator->fails()) {
                return redirect()->route('item.view')->withErrors($validator)->withInput();                         
            }

            $filenameWithExt = $request->file('image')->getClientOriginalName();        
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);        
            $extension = $request->file('image')->getClientOriginalExtension();        
            $fileNameToStore = $filename.'_'.time().'.'.$extension;        
            $path = $request->file('image')->storeAs('public/images/item/', $fileNameToStore);            
        }
    

        $item = Item::find($request->input('id'));

        $item->desc = $request->input('desc');
        $item->item_category_id = $request->input('category_id');

        if($request->input('all_types') == 1)
            $item->pet_type_id = null;            
        else 
            $item->pet_type_id = $request->input('type_id');

        if($request->hasFile('image')){
            if(!empty($item->image))
                Storage::disk('public')->delete('images/item/' . $item->image);

            $item->image = $fileNameToStore;   
        }
        

        $item->quantity = $request->input('quantity');
        $item->deal_price = $request->input('deal_price');
        $item->reg_price = $request->input('reg_price');

        $item->save();

        return redirect('admin/inventory')->with('info', 'Item '. ucfirst($item->desc) . 'has been successfully Updated');    

    } 

   

    public function export() 
    {
        return Excel::download(new ItemsExport, 'INVENTORY-'. \Carbon\Carbon::now()->isoFormat('OY-MMM-DD') . '.xlsx');
    }

    public function getQuantity($itemid){

        return Item::find($itemid);

    }


}
