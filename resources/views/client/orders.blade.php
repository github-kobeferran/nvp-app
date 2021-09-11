@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">
        
        <div class="col">

            @if (auth()->user()->isAdmin())
                <h3> {{ucfirst($the_client->user->first_name) . ' ' . ucfirst($the_client->user->last_name)}}'s orders</h3>
            @else
                <h3>My orders</h3>
            @endif

        </div>

    </div>

    

    @if (!is_null(\App\Models\Order::first()))

        <?php 
            $orders = \App\Models\Order::all();
            $clientOrders = collect();
            
            foreach ($orders as $order) {

                

                if($order->transaction->exists()){

                    if($order->transaction->client->id == $the_client->id)
                        $clientOrders->push($order);

                }

            }

        ?>

        @if (!is_null($clientOrders))

            <div class="table-responsive">

                <table id="orders" class="table table-bordered">

                    <thead class="bg-success text-white">

                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Mark as done by</th>
                    </thead>

                    <tbody>

                        @foreach ($clientOrders as $order)

                            <tr>
                                <td>
                                    @if ($order->status == 0)
                                        <span class="text-info">To Receive</span>
                                    @else
                                        <span class="text-muted">Order Received</span>
                                    @endif
                                </td>
                                <th>{{$order->transaction->trans_id}}</th>
                                <td>{{$order->item->desc}}</td>
                                <td class="text-center">{{$order->quantity}}</td>
                                <td>
                                    @if (!is_null($order->done_by))
                                       Admin {{\App\Models\User::find($order->done_by)->first_name }}                                    
                                    @else
                                        N\A
                                    @endif
                                </td>
                                    
                            </tr>
                            
                        @endforeach

                    </tbody>

                </table>

            </div>            

            
        @else

            <div class="text-center">

                No orders yet.

            </div>
            
        @endif

     
        
    @endif

</div>

<script>
$(document).ready( function () {
    $('#orders').DataTable();
} );    
</script>
    
@endsection