@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">                

        <div class="col text-center">
                        
            <h1>Orders</h1>            

            @include('inc.messages')

            <hr>            

        </div>

    </div>

    @if (!is_null(\App\Models\Order::first()))

        <div class="row">

            <div class="col">

                <div class="table-responsive">

                    <table class="table table-bordered text-center" id="orders">

                        <thead class="bg-success">

                            <th>STATUS</th>
                            <th>TRANSACTION ID</th>
                            <th>ITEM</th>
                            <th>QUANTIY</th>
                            <th>CLIENT NAME</th>
                            <th>CLIENT SEX</th>
                            <th>CLIENT ADDRESS</th>
                            <th>CLIENT CONTACT NUMBER</th>
                            <th>DONE BY</th>

                        </thead>

                        <tbody>

                            @foreach (\App\Models\Order::orderBy('status', 'asc')->orderBy('created_at', 'desc')->get() as $order)

                                <tr>
                                    <td>
                                        @switch($order->status)
                                            @case(0)
                                                <span class="text-info">Pending</span>
                                                <button data-toggle="modal" data-target="#order-{{$order->id}}" class="btn btn-sm btn-primary">Mark as Done <i class="fa fa-check" aria-hidden="true"></i></button>

                                                <div class="modal fade" id="order-{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                          <h5 class="modal-title" id="exampleModalLongTitle">ORDER with TRANSACTION ID #{{$order->transaction->trans_id}}</h5>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul class="list-group">
                                                                <li class="list-group-item">Client Name : <b>{{strtoupper($order->transaction->client->user->last_name) . ', ' . strtoupper($order->transaction->client->user->first_name) . ' ' . strtoupper($order->transaction->client->user->middle_name)}}</b></li>
                                                                <li class="list-group-item text-center">ORDER DETAILS: </li>
                                                                <li class="list-group-item text-center">{{strtoupper($order->item->desc)}} x {{$order->quantity}} </li>
                                                            </ul>
                                                        </div>
                                                        <div class="modal-footer">
                                                            {{Form::open(['url' => 'orderdone'])}}
                                                                {{Form::hidden("id", $order->id)}}
                                                                <button type="submit" class="btn btn-primary">Mark as Done  <i class="fa fa-check" aria-hidden="true"></i></button>
                                                            {{Form::close()}}
                                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                                @break
                                            @case(1)
                                                <a href="/myorders/{{$order->transaction->client->user->email}}" class="text-success">Done</a>
                                                @break                                       
                                            @default
                                                
                                        @endswitch
    
                                    </td>
                                    <th>{{$order->transaction->trans_id}}</th>
                                    <td>{{strtoupper($order->item->desc)}}</td>
                                    <td>{{$order->quantity}}</td>
                                    <td><a href="/user/{{$order->transaction->client->user->email}}" class="text-info">{{strtoupper($order->transaction->client->user->last_name) . ', ' . strtoupper($order->transaction->client->user->first_name) . ' ' . strtoupper($order->transaction->client->user->middle_name)}}</a></td>
                                    <td>{{$order->transaction->client->sex == 0 ? 'MALE' : 'FEMALE'}}</td>
                                    <td>{{strtoupper($order->transaction->client->address)}}</td>
                                    <td>{{strtoupper($order->transaction->client->contact)}}</td>
                                    <td>
                                        @if (!is_null($order->done_by))

                                            @if (is_null(\App\Models\User::find($order->done_by)->employee))
                                                Site Administrator
                                            @else
                                                Admin {{\App\Models\User::find($order->done_by)->first_name }}                                                                                    
                                            @endif
                                        @else
                                            N\A
                                        @endif
                                    </td>
                                </tr>
                               
                                
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
        
    @else

        <div class="row">

            <div class="col text-center">

                No records of orders yet.

            </div>

        </div>
        
    @endif

</div>

<script>
$(document).ready( function () {
    $('#orders').DataTable();
} );

</script>

@endsection