<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;
use App\Models\Item;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Payment;
use Carbon\Carbon;
use Carbon\Setting;

class OrdersController extends Controller
{

    public function store(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();                        

        $validator = Validator::make($request->all(), [
            'client_id' => 'required',                                                          
            'item_id' => 'required',
            'quantity' => 'required|numeric|gte:1',
        ]);

        if ($validator->fails()) {
            return redirect('inventory')->withErrors($validator);
        }

        $client = Client::find($request->input('client_id'));
        $item = Item::find($request->input('item_id')); 

        $order = new Order;
        $order->item_id = $item->id;
        $order->quantity = $request->input('quantity'); 
        $order->status = 1;
        $order->done_by = auth()->user()->id;

        $transaction = new Transaction;
        $transaction->client_id = $client->id;
        $transaction->type = 'x' . $request->input('quantity') . ' ' . $item->desc;
        $transaction->has_payment = 1;
        $transaction->approved_by = auth()->user()->id;
        $transaction->save();
        $transaction->trans_id = Carbon::now()->isoFormat('OY') . '-' . sprintf('%04d', $transaction->id);
        $transaction->save();

        $payment = new Payment;

        $payment->amount = $item->reg_price * $request->input('quantity');
        $payment->transaction_id = $transaction->id;
        $payment->save();

        $order->transaction_id = $transaction->id;
        
        $order->save();

        $item->quantity-= $request->input('quantity');
        $item->save();

        return redirect('/admin/orders')->with('success', 'Order Create Successfull');
        

    }
    
     public function clientStore($clientid, $itemid, $quantity){        

        $client = Client::find($clientid);
        $item = Item::find($itemid);  
        
        $order = new Order;
        $order->item_id = $item->id;
        $order->quantity = $quantity; 

        $transaction = new Transaction;
        $transaction->client_id = $client->id;
        $transaction->type = 'x' . $quantity . ' ' . $item->desc;
        $transaction->has_payment = 1;
        $transaction->save();
        $transaction->trans_id = Carbon::now()->isoFormat('OY') . '-' . sprintf('%04d', $transaction->id);
        $transaction->save();

        $payment = new Payment;

        $payment->amount = $item->reg_price * $quantity;
        $payment->transaction_id = $transaction->id;
        $payment->save();

        $order->transaction_id = $transaction->id;
        
        $order->save();

        $item->quantity-= $quantity;
        $item->save();

        return redirect('/inventory')->with('success', 'Order Successfull, will deliver at address in you account details within this day thank you.');

    }

    public function view(){

        return view('admin.view.orders');

    }

    public function done(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();

        $order = Order::find($request->input('id'));

        $order->status = 1;
        $order->done_by = auth()->user()->id;

        $order->save();
        
        return redirect('/admin/orders')->with('success', 'ORDER MARKED AS DONE');

    }

}
