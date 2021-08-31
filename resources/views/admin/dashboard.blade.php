@extends('layouts.app')

{{-- @include('inc.banner') --}}

@section('content')

@include('inc.sidebar')

<div class="container">

    <h1 class="m-2 bg-info text-white border border-primary text-center rounded">ADMIN DASHBOARD</h1>

    <div class="row">

        <div class="col">
            
            <a href="{{url('/admin/clients')}}" class="btn btn-secondary btn-block">
                @if(\App\Models\User::where('user_type', 0)->count() < 1 )
                No Clients
                @else
                Client{{\App\Models\Client::count() > 0 ? 's' : ''}} <span class="badge badge-light">{{\App\Models\Client::count()}}</span>
                @endif
            </a>

        </div>

        <div class="col">

            <a href="{{url('/admin/pets')}}"  class="btn btn-danger btn-block">
                @empty(\App\Models\Pet::first() )
                No Pets
                @else
                Pet{{\App\Models\Pet::count() > 0 ? 's' : ''}} <span class="badge badge-light">{{\App\Models\Pet::count()}}</span>
                @endempty
            </a>

        </div>

        <div class="col">

            <a href="{{url('/admin')}}" class="btn btn-primary btn-block">
                @empty(\App\Models\Appointment::all())
                No Appointments
                @else
                Appointment{{\App\Models\Appointment::where('done', 0)->count() > 0 ? 's' : ''}} <span class="badge badge-light">{{\App\Models\Appointment::count()}}</span>
                @endempty
            </a>

        </div>

    </div>

    

    <div class="container text-center my-5 py-2 border border-info">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @include('inc.messages')

        <h2>Set an Appointment</h2>

        {!!Form::open(['url' => '/storeappointment'])!!}     
        <hr>   
            
        <div class="form-group">

            <label for="type">Client</label>

            <?php                     
                $users = \App\Models\User::where('user_type', 0)->get();                    

                foreach ($users as $user) {
                    $user->client;
                }                        

                $user_clients = collect();
                
                foreach ($users as $user) {

                    $user_clients->push(collect(['name' => $user->name, 'id' => $user->client->id]));

                }                
                
                $list = $user_clients->pluck('name', 'id');
                $list->all();

            ?>        
             {{Form::select('client_id', $list , null, ['id' => 'clientSelect', 'title' => 'Select Client', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}

        </div>   
        
        <div class="form-group">
        
            <label for="type">Pet</label>   
        
            {{Form::select('pet_id', [] , null, ['id' => 'petSelect', 'class' => 'form-control w-25 ml-2 border mx-auto', 'required' => 'required'])}}
        
        </div>  

        <div class="form-group">

            <label for="type">Service</label>

            <?php                     
         
                $services = \App\Models\Service::orderBy('desc', 'asc')->get()->pluck('desc', 'id');                

            ?>   

            {{Form::select('service_id', $services , null, ['title' => 'Select Service', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}

        </div>  


        <hr>

        <div class="form-group">
            <label for="">From:</label>
            <div class="input-group date w-50 mx-auto" id="datetimepicker1" data-target-input="nearest">
                <input type="text" name="start_time"class="form-control datetimepicker-input" data-target="#datetimepicker1" required/>
                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="">To:</label>
            <div class="input-group date w-50 mx-auto" id="datetimepicker2" data-target-input="nearest">
                <input type="text" name="end_time"class="form-control datetimepicker-input" data-target="#datetimepicker2" required/>
                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>

        <hr>

        <button class="btn btn-block btn-primary w-50 mx-auto">Set Appointment</button>
    

        {!!Form::close()!!}


    </div>


    @empty(\App\Models\Appointment::first())
    
    @else
        
    <div class="container mt-2">
        
        <h3 class="mt-3 text-center " style="text-shadow: 1px 1px 10px rgb(112, 112, 112);">Appointments</h3>
        
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item border-bottom-0">
                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Today  <span class="badge badge-info text-white"> {{\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->count()}}</span></a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">This Week ({{\Carbon\Carbon::now()->startOfWeek()->isoFormat('MMM DD') . '-' . \Carbon\Carbon::now()->endOfWeek()->isoFormat('MMM DD')}})</a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Month of {{\Carbon\Carbon::now()->monthName}}</a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">Appointment History</a>
            </li>
        </ul><!-- Tab panes -->


        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">

                <div class="container m-4">  

                    @empty(\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->first())

                    <b class="text-center">No Appointments Today</b>

                    @else

                        <table class="table table-bordered">
                            <thead class="bg-warning ">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Pet</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach (\App\Models\Appointment::whereBetween('start_time',
                                                                                [\Carbon\Carbon::now()->startOfDay(), 
                                                                                \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                                                                                [\Carbon\Carbon::now()->startOfDay(), 
                                                                                \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->orderBy('start_time','asc')->get() as $appointment)
                                    
                                    <tr>         
                                        <td>{{$appointment->service->desc}}</td>    

                                        <td><a href="{{url('/user/'.$appointment->client->user->email )}}">{{$appointment->client->user->name}}</a></td>
                                        {{-- <td>{{$appointment->pet->name}}</td> --}}

                                        <td><a href="{{url('/pet/'.$appointment->client->user->email . '/' . $appointment->pet->name )}}">{{ucfirst($appointment->pet->name)}}</a></td>
                                        
                                        <td>{{\Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#markAppointment-{{$appointment->id}}">
                                                Mark as Done
                                            </button> 
                                        </td>
                                    </tr>  
                                    
                                    
                                    <div class="modal fade"  id="markAppointment-{{$appointment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title " id="exampleModalLongTitle">{{$appointment->service->desc . ' : ' . $appointment->client->user->name}}</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">

                                                Client : {{ucfirst($appointment->client->user->name)}}
                                                <br>
                                                Service : {{$appointment->service->desc}}
                                                <hr>
                                                Fee : {{$appointment->service->price}}
                                                <hr>
                                                Mark this appointment as <b>DONE?</b>

                                            </div>
                                            {!!Form::open(['url' => '/appointmentdone'])!!}
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                
                                                {{Form::hidden('id', $appointment->id)}}
                                                {{Form::hidden('pet_id', $appointment->pet->id)}}
                
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
            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <div class="container m-4">
                    @empty(\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfWeek(), 
                    \Carbon\Carbon::now()->endOfWeek()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfWeek(), 
                    \Carbon\Carbon::now()->endOfWeek()])->where('done', 0)->first())

                    <b class="text-center">No Appointments this Week</b>

                    @else

                        <table class="table table-bordered">
                            <thead class="bg-warning ">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Time</th>                                                                        
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach (\App\Models\Appointment::whereBetween('start_time',
                                                                                [\Carbon\Carbon::now()->startOfWeek(), 
                                                                                \Carbon\Carbon::now()->endOfWeek()])->whereBetween('end_time',
                                                                                [\Carbon\Carbon::now()->startOfWeek(), 
                                                                                \Carbon\Carbon::now()->endOfWeek()])->where('done', 0)->orderBy('start_time','asc')->get() as $appointment)
                                    
                                    <tr>         
                                        <td>{{$appointment->service->desc}}</td>    

                                        <td><a href="{{url('/user/'.$appointment->client->user->email )}}">{{$appointment->client->user->name}}</a></td>
                                        
                                        <td>{{\Carbon\Carbon::parse($appointment->start_time)->isoFormat('MMM D, OY') . ' ' . \Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a') }}</td>
                                       
                                    </tr>  
                                                                                                       

                                @endforeach
                            </tbody>
                        </table>
                        
                    @endempty
                </div>
            </div>
            <div class="tab-pane" id="tabs-3" role="tabpanel">
                <div class="container m-4">
                    @empty(\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->first())

                    <b class="text-center">No Appointments Today</b>

                    @else

                        <table class="table table-bordered">
                            <thead class="bg-warning ">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach (\App\Models\Appointment::whereBetween('start_time',
                                                                                [\Carbon\Carbon::now()->startOfMonth(), 
                                                                                \Carbon\Carbon::now()->endOfMonth()])->whereBetween('end_time',
                                                                                [\Carbon\Carbon::now()->startOfMonth(), 
                                                                                \Carbon\Carbon::now()->endOfMonth()])->where('done', 0)->orderBy('start_time','asc')->get() as $appointment)
                                    
                                    <tr>         
                                        <td>{{$appointment->service->desc}}</td>    

                                        <td><a href="{{url('/user/'.$appointment->client->user->email )}}">{{$appointment->client->user->name}}</a></td>
                                        
                                        <td>{{\Carbon\Carbon::parse($appointment->start_time)->isoFormat('MMM D, OY') . ' ' . \Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a') }}</td>
                                    </tr>                                                                    
                            

                                @endforeach
                            </tbody>
                        </table>
                        
                    @endempty
                </div>
            </div>
            <div class="tab-pane" id="tabs-4" role="tabpanel">
                <div class="container m-4">
                    
                    @empty(\App\Models\Appointment::first())

                    @else

                        <i class="fa fa-history" aria-hidden="true"></i> Appointment History

                        <table id="appointment-history" class="table table-bordered">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Schedule</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach (\App\Models\Appointment::where('done', 1)->orderBy('updated_at', 'desc')->get() as $appointment)
                                    
                                    <tr>         
                                        <td>{{$appointment->service->desc}}</td>    

                                        <td><a href="{{url('/user/'.$appointment->client->user->email )}}">{{$appointment->client->user->name}}</a></td>
                                        
                                        <td>{{\Carbon\Carbon::parse($appointment->start_time)->isoFormat('MMM D, OY') . ' ' . \Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a') }}</td>
                                    
                                    </tr>  

                                @endforeach
                            </tbody>
                        </table>

                        
                    @endempty
                    
                </div>
            </div>
        </div>
        

    </div>
    @endempty


</div>

<script>

let petSelect = document.getElementById('petSelect');
let clientSelect = document.getElementById('clientSelect');

clientSelect.addEventListener('change', () => {

    let client_id = clientSelect.value;

    let xhr = new XMLHttpRequest();        

    xhr.open('GET', APP_URL + '/getclientpets/' + client_id , true);

    xhr.onload = function() {
        if (this.status == 200) {                 
            
            let pets = JSON.parse(this.responseText);  
                                        
            
            let output = `<select name="pet_id" class="custom-select ml-2 border w-25 mx-auto" id="petSelect"   required>`;                                  
            for(let i in pets){
                output+=`<option value="`+ pets[i].id +`" >` + capitalizeFirstLetter(pets[i].name) +`</option>`;                        
            }
            output+=`</select>`;         
                    
            
            petSelect.innerHTML = output;
            
                        
        }              

    }

    xhr.send(); 

    

});


$(function() {
$('#datetimepicker1').datetimepicker({      
});
$('#datetimepicker2').datetimepicker();
});


$(document).ready( function () {
    // $('#appointment-history').DataTable(
    //     {
    //     "order": [[ 0, "desc" ]]
    // });
    $('#appointment-history').DataTable();
} );




</script>

@endsection