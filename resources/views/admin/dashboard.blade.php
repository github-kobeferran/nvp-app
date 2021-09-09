@extends('layouts.app')

@section('content')

@if (is_null(\App\Models\Setting::first()))    
    <?php \App\Models\Setting::create([]); ?>
@endif

@include('inc.sidebar')

<div class="container">

    <h1 class="m-2 bg-info text-white border border-primary text-center rounded">ADMIN DASHBOARD</h1>

    <div class="row">

        <div class="col">
            
            <a href="{{url('/admin/clients')}}" class="btn btn-secondary btn-block">
                @if(\App\Models\User::where('user_type', 0)->count() < 1 )
                No Clients
                @else
                Client{{\App\Models\User::where('user_type', 0)->count() > 0 ? 's' : ''}} <span class="badge badge-light">{{\App\Models\User::where('user_type', 0)->count()}}</span>
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
                Appointment{{\App\Models\Appointment::where('status', 0)->count() > 0 ? 's' : ''}} <span class="badge badge-light">{{\App\Models\Appointment::count()}}</span>
                @endempty
            </a>

        </div>

    </div>

    <div class="my-1">
        @include('inc.messages')
    </div>

    <div class="container my-2 border border-secondary p-2">

        {{Form::open(['url' => 'updatesetting'])}}

        <div class="row ">

            <div class="col-3">
                <h2 class="mx-auto">Settings</h2> 
            </div>
            <div class="col">
                <button id="submitButton" type="submit" class="btn btn-success ml-2 rounded-0 " disabled>Save changes</button>                
            </div>
            <div class="col-2 text-right mr-2">
                <button onclick="toggleSettingsPanel()" type="button" class="btn btn-sm btn-dark ml-2  "><i  id="caret" class="fa fa-caret-down" aria-hidden="true"></i></button>                
            </div>

        </div>
        

        <div class="row d-none " id="settingspanel">

            <div class="col ">

                        
                    <div class="d-flex justify-content-start flex-wrap bg-dark text-white border border-primary">

                        <div class="form-inline mx-auto my-2 text-left">

                            <div class="input-group px-2">
    
                                <label for="">Max Clients/Day</label>
                                {{Form::number('max_clients', \App\Models\Setting::first()->max_clients, ['class' => 'form-control ml-2 rounded-0', 'max' => '30', 'min' => '1'])}}
    
                            </div>
    
                        </div>

                        <div class="form-inline mx-auto my-2 text-left">
                            <label for="">Appointment Fee</label>

                            <div class="input-group px-2">
    
                                &#8369;{{Form::number('appointment_fee', \App\Models\Setting::first()->appointment_fee, ['class' => 'form-control ml-2 rounded-0', 'max' => '5000', 'min' => '50'])}}
    
                            </div>
    
                        </div>

                        <div class="form-group mx-auto my-2 text-left">

                            <div class="custom-control custom-switch">
                                <input name="stop_appointments" type="checkbox" class="custom-control-input" id="customSwitch1" {{ \App\Models\Setting::first()->stop_appointments ? 'checked' : ''}}>
                                <label class="custom-control-label" for="customSwitch1">Stop Accepting Appointments</label>
                            </div>                                
    
                        </div>

                        <div class="form-group mx-auto my-2 text-left">

                            <div class="custom-control custom-switch">
                                <input name="stop_orders"  type="checkbox" class="custom-control-input" id="customSwitch2" {{ \App\Models\Setting::first()->stop_orders ? 'checked' : ''}}>
                                <label class="custom-control-label" for="customSwitch2">Stop Accepting Orders</label>
                            </div>                                
                                
    
                        </div>

                    </div>                    

                    
                </div>
                
            </div>
            
            {{Form::close()}}
    </div>
    
    
    @if (!is_null(\App\Models\User::where('user_type', 0)->first() ))

    <div class="container text-center mb-5 py-2 border border-info">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        
{{-- ------------------------------APPOINTMENTS FORM --}}
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

                    $user_clients->push(collect(['name' => ucfirst($user->first_name) . ' ' . strtoupper(substr($user->middle_name, 0, 1)) . '. ' . ucfirst($user->last_name), 'id' => $user->client->id]));

                }                
                
                $list = $user_clients->pluck('name', 'id');
                $list->all();

            ?>        
                
                
            {{Form::select('client_id', $list , null, ['id' => 'clientSelect', 'title' => 'Select Client', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border border-info rounded-0', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}
                
        </div>  
         
            
        <div class="form-group">
            
            <label for="type">Pet</label>   
            {{Form::select('pet_id', [] , null, ['title' => 'Choose Pet', 'id' => 'petSelect', 'class' => 'selectpicker ml-2 border border-info rounded-0', 'required' => 'required'])}}            
            
        </div>  
        
        <div class="form-group">
            
            <label for="type">Service</label>
            
            <?php                     
        
                $services = \App\Models\Service::where('status', 0)->orderBy('desc', 'asc')->get()->pluck('desc', 'id');                
            
            ?>   
            
            {{Form::select('services[]', $services , null, ['multiple' => 'multiple', 'title' => 'Select Service', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border border-info rounded-0', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}            

        </div>  


        <hr>

        <div class="form-group">
            <label for="">Appointment Date</label>
            {{Form::date('date', \Carbon\Carbon::tomorrow(), ['class' => 'form-control text-center mx-auto rounded-0', 'required' => 'required'])}}
        </div>           

        <button class="btn btn-block btn-primary rounded-0 mb-2">Set Appointment</button>


    {!!Form::close()!!}

{{-- ------------------------------APPOINTMENTS FORM --}}
    </div>

@endif

@empty(\App\Models\Appointment::first())
    
    @else
        
    <div class="container mt-2">
        
        <h3 class="mt-3 text-center " style="text-shadow: 1px 1px 10px rgb(112, 112, 112);">Appointments</h3>
        
        <ul class="nav nav-tabs mb-2" role="tablist">
            <li class="nav-item border-bottom-0">
                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                    Today
                    <span class="badge badge-warning">
                        {{\App\Models\Appointment::where('date', \Carbon\Carbon::now()->toDateString())->where('status', 0)->count()}}
                    </span>
                </a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">
                    Tomorrow
                    <span class="badge badge-info text-white">
                        {{\App\Models\Appointment::where('date', \Carbon\Carbon::tomorrow()->toDateString())->where('status', 0)->count()}}
                    </span>
                </a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">All Appointments</a>
            </li>
            
        </ul>
        <!-- Tab panes -->

        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">

                <div class="container ">  

                    @empty(\App\Models\Appointment::where('date', \Carbon\Carbon::now()->toDateString())->where('status', 0)->first())

                    <b class="text-center">No Appointments Today</b>

                    @else

                        <div class="table-responsive">

                            <table id="today-appointments" class="table table-bordered">
                                <thead class="bg-warning ">
                                    <tr>
                                        <th>No.</th>
                                        <th>Service</th>
                                        <th>Client</th>
                                        <th>Pet</th>                                    
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 0; ?>
                                    @foreach (\App\Models\Appointment::where('date', \Carbon\Carbon::now()->toDateString())->where('status', 0)->orderBy('created_at','asc')->get() as $appointment)
                                        
                                        <tr>         
                                            <td>{{++$count}}.</td>    
                                            <td>
                                                @foreach ($appointment->services() as $service)
                                                
                                                    @if($loop->last)
                                                        {{$service->desc}}
                                                    @else                                                
                                                        {{$service->desc . ', '}}
                                                    @endif
                                                    
                                                @endforeach
                                            </td>    

                                            <td><a href="{{url('/user/' . $appointment->pet->owner->user->email )}}">{{$appointment->pet->owner->user->first_name . ' ' . substr($appointment->pet->owner->user->middle_name, 0, 1) . '. ' . ucfirst($appointment->pet->owner->user->last_name)}}</a></td>


                                            <td><a href="{{url('/pet/' . $appointment->pet->owner->user->email . '/' . $appointment->pet->name )}}" class="text-danger">{{ucfirst($appointment->pet->name)}}</a></td>
                                            
                                            <td>
                                                <button type="button" class="btn btn-success mb-1" data-toggle="modal" data-target="#markAppointment-{{$appointment->id}}">
                                                    Mark as Done
                                                </button> 
                                                <button type="button" class="btn btn-secondary mb-1" data-toggle="modal" data-target="#abandon-{{$appointment->id}}">
                                                    Mark as Abandoned
                                                </button> 
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reschedule-{{$appointment->id}}">
                                                    Reschedule
                                                </button> 

                                                <div class="modal fade"  id="markAppointment-{{$appointment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title " id="exampleModalLongTitle"><span class="text-dark">Appointment of </span>{{$appointment->pet->owner->user->first_name . ' ' . $appointment->pet->owner->user->last_name}} : {{$appointment->pet->name}}</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
        
                                                            {!!Form::open(['url' => '/appointmentdone'])!!}
                                                                <div class="modal-body">
        
                                                                    <span class="text-muted">Client</span> : {{$appointment->pet->owner->user->first_name . ' ' . substr($appointment->pet->owner->user->middle_name, 0, 1) . '. ' . $appointment->pet->owner->user->last_name}}
                                                                    <br>
                                                                    <div>
                                                                        <span class="text-muted">Services</span> :  
                                                                        <?php $fee = 0; ?>
                                                                        @foreach ($appointment->services() as $service)
                                                                            <?php $fee+= $service->price; ?>
                                                                            @if($loop->last)
                                                                                {{$service->desc}}
                                                                            @else                                                
                                                                                {{$service->desc . ', '}}
                                                                            @endif
                                                                        
                                                                        @endforeach
                                                                    </div>
                                                                    <hr>
                                                                    <span><span class="text-muted">Fee</span> : &#8369; {{$fee}}</span>
                                                                    <hr>
                                                                    <span class="text-muted">Mark this appointment as </span><b>DONE?</b>
        
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Yes</button>                    
                                                                    
                                                                    {{Form::hidden('id', $appointment->id)}}
                                                                    {{Form::hidden('pet_id', $appointment->pet->id)}}
                                                                    
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            {!!Form::close()!!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade"  id="abandon-{{$appointment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-secondary text-white">
                                                                <h5 class="modal-title " id="exampleModalLongTitle">Mark as abandon</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
        
                                                            {!!Form::open(['url' => '/abadonappointment'])!!}
                                                                <div class="modal-body">
                                                                                                                                            
                                                                    {{Form::hidden('id', $appointment->id)}}                                                                                                  

                                                                    <div>
                                                                        <span><span class="text-muted">Appointment of</span> {{$appointment->pet->owner->user->first_name . ' ' . $appointment->pet->owner->user->last_name}} : {{$appointment->pet->name}}</span>
                                                                    </div>

                                                                    <div>
                                                                        <span><span class="text-muted">Mark this appointment as </span>ABANDONED?</span>
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-outline-secondary">Yes</button>                    
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            {!!Form::close()!!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade"  id="reschedule-{{$appointment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title " id="exampleModalLongTitle">Re schedule appointment of Pet {{$appointment->pet->name}}</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
        
                                                            {!!Form::open(['url' => '/reschedappointment'])!!}
                                                                <div class="modal-body">
                                                                        
                                                                    <div class="form-group">
                                                                        Submitted appointment date: {{\Carbon\Carbon::parse($appointment->date)->isoFormat('MMM DD OY') }}
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <span class="text-muted">Set new appointment date</span>
                                                                        {{Form::date('date', $appointment->date, ['class' => 'form-control rounded-0'])}}
                                                                    </div>

                                                                    {{Form::hidden('id', $appointment->id)}}                                                                                                  
                                                                    {{Form::hidden('client_id', $appointment->pet->owner->id)}}                                                                                                  
                                                                    
                                                                </div>
                                                                
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save Changes</button>                    
                                                                </div>
                                                            {!!Form::close()!!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>                                                                                                                                                         

                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <script>

                            $(document).ready(function() {
                                $('#today-appointments').DataTable();
                            } );
                            
                        </script>
                        
                    @endempty
                    
                </div>
            </div>

            


            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <div class="container">
                  
                    @empty(\App\Models\Appointment::where('date', \Carbon\Carbon::tomorrow()->toDateString())->where('status', 0)->first())

                    <b class="text-center">No Appointments Tomorrow</b>

                    @else

                        <div class="table-responsive">

                            <table id="tomorrow-appointments" class="table table-bordered">
                                <thead class="bg-info text-white">
                                    <tr>
                                        <th>No.</th>
                                        <th>Service</th>
                                        <th>Client</th>
                                        <th>Pet</th>                                    
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 0; ?>
                                    @foreach (\App\Models\Appointment::where('date', \Carbon\Carbon::tomorrow()->toDateString())->where('status', 0)->orderBy('created_at','asc')->get() as $appointment)
                                        
                                        <tr>         
                                            <td>{{++$count}}.</td>    
                                            <td>
                                                @foreach ($appointment->services() as $service)
                                                
                                                    @if($loop->last)
                                                        {{$service->desc}}
                                                    @else                                                
                                                        {{$service->desc . ', '}}
                                                    @endif
                                                    
                                                @endforeach
                                                
                                            </td>    
    
                                            <td><a href="{{url('/user/' . $appointment->pet->owner->user->email )}}">{{$appointment->pet->owner->user->first_name . ' ' . substr($appointment->pet->owner->user->middle_name, 0, 1) . '. ' . ucfirst($appointment->pet->owner->user->last_name)}}</a></td>
    
    
                                            <td><a href="{{url('/pet/' . $appointment->pet->owner->user->email . '/' . $appointment->pet->name )}}" class="text-danger">{{ucfirst($appointment->pet->name)}}</a></td>
                                            
                                            <td>                                          
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reschedule-{{$appointment->id}}">
                                                    Reschedule
                                                </button> 

                                                <div class="modal fade"  id="reschedule-{{$appointment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title " id="exampleModalLongTitle">Re schedule appointment of Pet {{$appointment->pet->name}}</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
        
                                                            {!!Form::open(['url' => '/reschedappointment'])!!}
                                                                <div class="modal-body">
                                                                        
                                                                    <div class="form-group">
                                                                        Submitted appointment date: {{\Carbon\Carbon::parse($appointment->date)->isoFormat('MMM DD OY') }}
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <span class="text-muted">Set new appointment date</span>
                                                                        {{Form::date('date', $appointment->date, ['class' => 'form-control rounded-0'])}}
                                                                    </div>
        
                                                                    {{Form::hidden('id', $appointment->id)}}                                                                                                  
                                                                    {{Form::hidden('client_id', $appointment->pet->owner->id)}}                                                                                                  
                                                                    
                                                                </div>
                                                                
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save Changes</button>                    
                                                                </div>
                                                            {!!Form::close()!!}
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                            </td>
                                        </tr>  
                                        
                                        
    
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <script>

                            $(document).ready(function() {
                                $('#tomorrow-appointments').DataTable();
                            } );
                            
                        </script>
                        
                    @endempty

                </div>
            </div>
            
            <div class="tab-pane" id="tabs-3" role="tabpanel">
                <div class="container">
                    @empty(\App\Models\Appointment::first())

                    <b class="text-center">No Appointments on recorded</b>

                    @else

                        <div class="table-responsive">

                            <table id="all-appointments" class="table table-bordered">
                                <thead class="bg-secondary text-white">
                                    <tr>
                                        <th>Date</th>
                                        <th>Service</th>
                                        <th>Client</th>
                                        <th>Pet</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach (\App\Models\Appointment::orderBy('status', 'asc')->orderBy('created_at', 'desc')->get() as $appointment)
                                        
                                    <tr>         
                                            <td>{{\Carbon\Carbon::parse($appointment->date)->isoFormat('Do, MMMM OY')}}</td>
                                            <td>
                                               
                                                @foreach ($appointment->services() as $service)
                                                    
                                                    @if($loop->last)
                                                        {{$service->desc}}
                                                    @else                                                
                                                        {{$service->desc . ', '}}
                                                    @endif
                                                
                                                @endforeach
                                                
                                            </td>    
    
                                            <td><a href="{{url('/user/'.$appointment->pet->owner->user->email )}}">{{$appointment->pet->owner->user->first_name . ' ' . substr($appointment->pet->owner->user->first_name, 0, 1) . '. ' . $appointment->pet->owner->user->last_name}}</a></td>
                                            <td><a href="{{url('/pet/' . $appointment->pet->owner->user->email . '/' . $appointment->pet->name )}}" class="text-danger">{{ucfirst($appointment->pet->name)}}</a></td>
                                            
                                            <td>
                                                @switch($appointment->status)
                                                    @case(0)
                                                        <span class="text-info">Pending</span>
                                                                                            
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reschedthis-{{$appointment->id}}">
                                                            Reschedule
                                                        </button> 
        
                                                        <div class="modal fade"  id="reschedthis-{{$appointment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary text-white">
                                                                        <h5 class="modal-title " id="exampleModalLongTitle">Re schedule appointment of Pet {{$appointment->pet->name}}</h5>
                                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                
                                                                    {!!Form::open(['url' => '/reschedappointment'])!!}
                                                                        <div class="modal-body">
                                                                                
                                                                            <div class="form-group">
                                                                                Submitted appointment date: {{\Carbon\Carbon::parse($appointment->date)->isoFormat('MMM DD OY') }}
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <span class="text-muted">Set new appointment date</span>
                                                                                {{Form::date('date', $appointment->date, ['class' => 'form-control rounded-0'])}}
                                                                            </div>
                
                                                                            {{Form::hidden('id', $appointment->id)}}                                                                                                  
                                                                            {{Form::hidden('client_id', $appointment->pet->owner->id)}}                                                                                                  
                                                                            
                                                                        </div>
                                                                        
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Save Changes</button>                    
                                                                        </div>
                                                                    {!!Form::close()!!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        @break
                                                    @case(1)
                                                        <span class="text-success">Done</span>
                                                        @break
                                                    @case(2)
                                                        <span class="text-secondary">Abandoned</span>
                                                        @break                                                                                                    
                                                @endswitch
                                            </td>
                                        </tr>                                                                    
                                
    
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        
                        <script>

                            $(document).ready(function() {
                                $('#all-appointments').DataTable();
                            } );
                            
                        </script>

                    @endempty
                </div>
            </div>

          
        </div>
        

    </div>
    
@endempty


</div>

<script>



$(function() {
    $('#datetimepicker1').datetimepicker();
    $('#datetimepicker2').datetimepicker();
});

let petSelect = document.getElementById('petSelect');
let clientSelect = document.getElementById('clientSelect');

let startInput = document.getElementById('startInput');
let endInput = document.getElementById('endInput');

let settingspanel = document.getElementById('settingspanel');
let caret = document.getElementById('caret');
let submitButton = document.getElementById('submitButton');

clientSelect.addEventListener('change', () => {

    let client_id = clientSelect.value;

    let xhr = new XMLHttpRequest();        

    xhr.open('GET', APP_URL + '/getclientpets/' + client_id , true);

    xhr.onload = function() {
        if (this.status == 200) {                 
            
            let pets = JSON.parse(this.responseText);              
            
            let output = `<select name="pet_id" data-live-search="true" title="Choose a Pet" class="selectpicker w-25 ml-2 border border-info rounded-0 text-center mx-auto" id="petSelect" required>`;
            for(let i in pets){
                output+=`<option value="`+ pets[i].id +`">` + capitalizeFirstLetter(pets[i].name) +`</option>`;                        
            }
            output+=`</select>`;         
                    
            
            petSelect.innerHTML = output;

            $('.selectpicker').selectpicker('refresh');

        }              

    }

    xhr.send(); 

});


function toggleSettingsPanel(){

    if(settingspanel.classList.contains('d-none')){

        settingspanel.classList.remove('d-none');
        caret.className = 'fa fa-caret-up';
        submitButton.disabled = false;        

    } else{

        settingspanel.classList.add('d-none');
        caret.className = 'fa fa-caret-down';
        submitButton.disabled = true;        

    }

}


</script>

@endsection