<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Item;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Payment;
use Carbon\Carbon;

class OrdersController extends Controller
{
    
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

}
