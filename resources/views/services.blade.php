@extends('layouts.app')

@include('inc.banner')

@section('content')

   
    <div class="container text-center">

        <h1>Our Services</h1> 

        <hr>
        
        <table id="services" class="table table-bordered">
            <thead class="bg-info">
                <tr>
                    <th>Description</th>                                                
                    <th>Fee</th>                        
                    <th>Status</th>                        
                </tr>
            </thead>
            <tbody>
                @foreach (\App\Models\Service::orderBy('status', 'asc')->get() as $service)
                <tr>
                    <td>{{ucfirst($service->desc)}}</td>
                    <td>&#8369; {{$service->price}}</td>
                    <td>
                        @if ($service->status == 1)
                            Unavailable
                        @else
                            Available
                        @endif

                    </td>
                </tr>
                @endforeach
                
            </tbody>                
        </table>

    </div>


<script>

$(document).ready( function () {
    $('#services').DataTable();
} );

</script>
@endsection