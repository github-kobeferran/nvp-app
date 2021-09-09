@extends('layouts.app')

@section('content')

    <div class="container">      

        <div class="row">

            <div class="col">

                <h3>{{ $the_client->user->first_name . ' ' . $the_client->user->last_name . '\'s' }} Transactions</h3>

                @if ($the_client->transactions->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-bordered text-secondary" id="transactions"> 

                            <thead class="bg-success text-dark">
                                <th>ID</th>
                                <th>Type</th>
                                <th>Payment</th>
                                <th>Approved by</th>
                                <th>Done at</th>
                            </thead>

                            <tbody>
                                @foreach ($the_client->transactions as $transaction)
                                <tr>
                                    <th>{{$transaction->trans_id}}</th>
                                    <td>{{$transaction->type}}</td>
                                    <td>
                                        @if ($transaction->has_payment)
                                            &#8369; {{$transaction->payment->amount}}
                                        @else
                                            None
                                        @endif
                                    </td>
                                    <td>
                                        @if (is_null($transaction->approved_by))
                                            None
                                        @else

                                            @if (is_null(\App\Models\User::find($transaction->approved_by)->employee))
                                                Site Administrator
                                            @else
                                                Admin {{\App\Models\User::find($transaction->approved_by)->first_name . ' ' . \App\Models\User::find($transaction->approved_by)->last_name }}
                                            @endif
                                            
                                        @endif
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($transaction->created_at)->isoFormat('MMM-DD-OY') }}</td>

                                </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                    
                @else

                No records yet.

                @endif

            </div>

        </div>
            

    </div>

<script>

    $(document).ready(function() {
        $('#transactions').DataTable();
    } );
    
</script>

@endsection