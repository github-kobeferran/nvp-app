@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="col text-center">

            <h3>Transactions</h3>

        </div>

    </div>

    <div class="row">

        <hr>

        @if (!is_null(\App\Models\Transaction::first()))
        
        <div class="col">

            <div class="table-responsive">

                <table id="transactions" class="table table-bordered">

                    <thead class="bg-success">
                        <th>DATE</th>                        
                        <th>TRANSACTION ID</th>                        
                        <th>CLIENT</th>                        
                        <th>TYPE</th>                        
                        <th>PAYMENT</th>                        
                        <th>APPROVED BY</th>                        
                    </thead>                
                    <tbody>
                        @foreach (\App\Models\Transaction::all() as $transaction)
                            
                            <tr>
                                <td class="text-muted">{{\Carbon\Carbon::parse($transaction->created_at)->isoFormat('MMM DD, OY') }}</td>
                                <td >{{strtoupper($transaction->trans_id)}}</td>
                                <td class="text-muted">{{ucfirst($transaction->client->user->first_name) . ' ' . ucfirst($transaction->client->user->last_name)}}</td>
                                <td class="text-muted">{{$transaction->type}}</td>    
                                
                                
                                <td>
                                    
                                    @if ($transaction->has_payment)
                                        &#8369; {{$transaction->payment->amount }}
                                    @else
                                        <p class="text-muted">N\A</p>
                                    @endif

                                </td>

                                <td>

                                    @if (!is_null($transaction->approved_by))
                                        {{ucfirst(\App\Models\User::find(ucfirst($transaction->approved_by))->first_name) . ' ' . ucfirst(\App\Models\User::find(ucfirst($transaction->approved_by))->last_name)}}
                                    @else
                                    <p class="text-muted">N\A</p>
                                    @endif

                                </td>
                                                                                                    
                            </tr>

                        @endforeach    
                    </tbody>    

                </table>

            </div>

        </div>

        @else
                        
            <div class="col text-center">

                No records yet.
            </div>

        @endif





    </div>

   

</div>

<script>
$(document).ready(function() {
    $('#transactions').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
} );
</script>
    
@endsection