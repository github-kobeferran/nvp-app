@extends('layouts.app')

@include('inc.banner')

@section('content')

@include('inc.sidebar')

    <div class="container">

        <h1>Services</h1>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @include('inc.messages')

        <div class="container ml-2">

            <hr>

            <h3 ><i class="fa fa-plus text-dark" style="font-size: 1rem;"></i> Add a Service</h3>            

            <hr>

            {!!Form::open(['url' => '/storeservice'])!!}

                <div class="form-group">
                    <label for="">Service Description</label>
                    {{Form::text('desc', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Description here..'])}}
                </div>

                <label for="">Service Fee</label>
                <div class="form-inline">
                    &#8369; {{Form::number('price', 50, ['class' => 'form-control ml-2', 'min' => '50', 'max' => '50000'])}}
                </div>

                <button type="submit" class="btn btn-lg btn-primary float-right my-2">Submit</button>                
                <br>
                <br>
                <hr>

            {!!Form::close()!!}

        </div>

        @empty(\App\Models\Service::all())
            

        @else
            <br>
           <hr class="w-50 bg-dark">

           <h3 class="text-center">Services Table</h3>

            <table id="servicesTable" class="table table-striped table-bordered" style="">

                <thead class="bg-primary text-white">
    
                    <tr>
    
                        <th>Description</th>
                        <th>Fee</th>
                        <th style="width: 20px;">Action</th>
    
                    </tr>
    
                </thead>
    
                <tbody>
    
                    @foreach (\App\Models\Service::all() as $service)

                        <tr>
                            <td>{{$service->desc}}</td>
                            <td>&#8369; {{$service->price}}</td>
                            <td colspan="2" >

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editService-{{$service->id}}">
                                    Edit
                                    </button> 
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteService-{{$service->id}}">
                                    Delete
                                </button> 

                            </td>
                        </tr>

                        <div class="modal fade"  id="editService-{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit <b>{{ucfirst($service->desc)}}</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                {!!Form::open(['url' => '/updateservice'])!!}
                                <div class="modal-body">

                                    {{Form::hidden('id', $service->id)}}
                                    <div class="form-group">
                                        <label for="">Service Description</label>
                                        {{Form::text('desc', $service->desc, ['class' => 'form-control', 'required' => 'required'])}}
                                    </div>
                                    
                                    <label for="">Fee</label>
                                    <div class="form-inline">

                                        &#8369;{{Form::number('price', $service->price, ['class' => 'form-control ml-2', 'min' => '50', 'max' => '50000'])}}

                                    </div>                                    

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>                                                
                                    <button type="submit" class="btn btn-primary">Save</button>        
                                </div>
                                {!!Form::close()!!}
                            </div>
                            </div>
                        </div>

                        <div class="modal fade"  id="deleteService-{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Delete <b>{{ucfirst($service->desc)}}</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                You sure you want to delete service: <b>{{ucfirst($service->desc)}}</b>?
                                </div>
                                {!!Form::open(['url' => '/deleteservice'])!!}
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    
                                    {{Form::hidden('id', $service->id)}}
    
                                    <button type="submit" class="btn btn-primary">Yes</button>
    
                                {!!Form::close()!!}
                                </div>
                            </div>
                            </div>
                        </div>
                        
                    @endforeach
    
                </tbody>
    
    
            </table>
    
            
        @endempty


        
    </div>

<script>

$(document).ready( function () {
    // $('#itemsTable').DataTable(
    //     {
    //     "order": [[ 0, "desc" ]]
    // });
    $('#servicesTable').DataTable();
} );

</script>

@endsection