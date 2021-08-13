@extends('layouts.app')

@include('inc.banner')

@section('content')

    @empty(\App\Models\Service::all())
        
    @else

        <div class="container text-center">

            <h1>Our Services</h1> 

            <hr>
            
            <table id="services" class="table table-bordered">
                <thead class="bg-info">
                    <tr>
                        <th>Description</th>                                                
                        <th>Fee</th>                        
                    </tr>
                </thead>
                <tbody>
                    @foreach (\App\Models\Service::orderBy('desc', 'asc')->get() as $service)
                    <tr>
                        <td>{{ucfirst($service->desc)}}</td>
                        <td>&#8369; {{$service->price}}</td>
                    </tr>
                    @endforeach
                    
                </tbody>                
            </table>

        </div>

    @endempty


<script>

$(document).ready( function () {
    $('#services').DataTable();
} );

</script>
@endsection