@extends('layouts.app')

@include('inc.banner')

@section('content')

<script>

function getSelectValues(select) {
    let result = [];
    let options = select && select.options;
    let opt;

    for (let i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];

        if (opt.selected) {
        result.push(opt.value || opt.text);
        }
    }
    return result;

}

</script>

<div class="my-1 text-center">
    @include('inc.messages')
</div>

<div class="container">

    <div class="text-center">

        @if(is_null($pet->image))

            <img class="pet-profile-pic" src="{{url('storage/images/pet/no-image-pet.jpg')}}" alt="">

        @else

            <img class="pet-profile-pic" src="{{url('storage/images/pet/' . $pet->image)}}" alt="">
           
        @endif     

        <h2 class="m-2 text-secondary "><i class="fa fa-paw" style="font-style: italic;" aria-hidden="true"></i> {{ucfirst($pet->name)}} <i class="fa fa-paw" style="font-style: italic;" aria-hidden="true"></i></h2>
        <em> <h5 class="m-2 text-secondary ">Birthday: {{$pet->dob_string}} | {{$pet->age}}</h5></em>

    </div>   
    
    <div class="table-responsive-lg">

        <table class="table table-bordered table-client text-center p-5">

            @if (auth()->user()->isAdmin())
                        
                <tr>
                    <td>Owner: <a href="{{url('/user/' .  $pet->owner->user->email)}}">{{ucfirst($pet->owner->user->first_name) . ' ' . strtoupper(substr($pet->owner->user->middle_name, 0, 1)) . ' ' . ucfirst($pet->owner->user->last_name)}}</a></td>  
                </tr>
                
            @endif
            
            @if(is_null($pet->sex))
                
            @else
                <tr>
                    <td>{{$pet->sex ? 'Female' : 'Male'}}</td>  
                </tr>
            @endif        

            @if(is_null($pet->type))            
                
            @else
                <tr>
                    <td>{{ucfirst($pet->type->type)}} : <em>{{ucfirst($pet->breed)}}</em></td>  
                </tr>
            @endif        

            @if (isset($pet->weight) && isset($pet->height))
            
                <tr>
                    <td>{{$pet->weight}} kg | {{$pet->height}} cm</td>
                </tr>

            @elseif(isset($pet->weight) && !isset($pet->height))

                <tr>
                    <td>{{$pet->weight}} kg </td>
                </tr>

            @elseif(!isset($pet->weight) && isset($pet->height))

                <tr>
                    <td>{{$pet->height}} cm </td>
                </tr>

            @endif

            <tr>
                <td>
                    @if ($pet->checked)
                        Have visited the Clinic                        
                        @if (!is_null($pet->last_appointment_at))
                            <span class="text-muted" style="font-size: .8em !important;"> last appointment at {{\Carbon\Carbon::parse($pet->last_appointment_at)->isoFormat('MMM DD, OY')}}</span>
                        @endif
                    @else
                        Have not visited the Clinic
                    @endif
                </td>
                
            </tr>

            @if (!auth()->user()->isAdmin())
                
                <tr>

                    <td><a href="{{url('/editpet/' . $pet->name)}}"> Edit {{$pet->name}}'s Details</a></td>

                </tr>

            @endif
                                

        </table>

    </div>

    <hr>

    @if (!auth()->user()->isAdmin())
        @if (\App\Models\Appointment::where('status', 0)->where('pet_id', $pet->id)->doesntExist())    
            <div class="row">

                <div class="col text-center">

                    <h4>Schedule an Appointment for {{$pet->name}}</h4>                    
                    
                    <div class="form-group">

                        <label for="type">Date <span id="dateStatus" class="text-success"></span></label>

                        {{Form::date('date', \Carbon\Carbon::today(), ['id' => 'dateInput', 'class' => 'form-control text-center w-50 mx-auto'])}}

                    </div>

                    <div class="form-group">
                
                        <a href="/services" class="">Service</a>
                        
                        <?php                     
                    
                            $services = \App\Models\Service::where('status', 0)->orderBy('desc', 'asc')->get()->pluck('desc', 'id');                                    
                        ?>   
                        
                        {{Form::select('services[]', $services , null, ['id' => 'serviceSelect', 'multiple' => 'multiple', 'title' => 'Select Service', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border border-info rounded-0', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}            
            
                    </div>  

                    <div id="costDiv" class="form-group d-none">

                        <label for=""><span class="text-muted">Service Cost: </span> &#8369;<span id="costLabel">12</span></label>

                        <div>
                            <button id="setAppointmentButton" type="button" data-toggle="modal" data-target="#payPal" class="btn btn-primary btn-block w-50 rounded-0 mx-auto">Set Appointment</button>
                        </div>

                        <script
                            src="https://www.paypal.com/sdk/js?client-id={{config('app.paypalClientID')}}&currency=PHP">
                        </script>                        

                        <div class="modal fade" id="payPal" tabindex="-1" role="dialog" aria-labelledby="payPalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Pay Appointment Fee</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        In order to set an appointment you have to pay for an appointment fee of &#8369; {{\App\Models\Setting::first()->appointment_fee}}                                        
                                        <div id="paypal-button-container"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <?php 
                            $merchant_id =  config('app.paypalMerchantID');
                            $petID =  $pet->id;
                        ?>

                        <script>

                        let appointment_fee = {!! json_encode(\App\Models\Setting::first()->appointment_fee) !!}
                        let merchant_id = {!! json_encode($merchant_id) !!}
                        let petID = {!! json_encode($petID) !!}
                        let date = document.getElementById('dateInput').value;                        
                                                

                        paypal.Buttons({
                                createOrder: function(data, actions) {                                                                
                                return actions.order.create({
                                    locale: 'en_US',                                   
                                    purchase_units: [{                                                    
                                        amount: {
                                            value: appointment_fee,
                                            currency_code: "PHP", 
                                            payee : merchant_id     
                                        }
                                    }]
                                });

                                },
                                onApprove: function(data, actions) {
                                    return actions.order.capture().then(function(details) {                                    
                                        window.location.replace('/setappointment/' + petID + '/' + date + '/' +  getSelectValues(document.getElementById('serviceSelect')).join(""));
                                    
                                    });
                                },
                                onCancel: function(data){                            
                                                                                            

                                }
                        }).render('#paypal-button-container');                                
                        

                        </script>   

                    </div>                    

                </div>

            </div>
        @endif

   @endif

    @if ($pet->appointments->count() > 0)

        <h3>{{ucfirst($pet->name)}}'s appointment history  <i class="fa fa-history" aria-hidden="true"></i></h3>

        <hr>

        <div class="table-responsive-lg">

            <table id="pet-appointment" class="table table-bordered table-client text-center p-5">

                <thead>

                    <th>Date</th>
                    <th>Service</th>
                    <th>Fee</th>
                    <th>Status</th>

                </thead>

                <tbody>
                    @foreach ($pet->appointments as $appointment)
                        <tr>
                            <td>{{\Carbon\Carbon::parse($appointment->date)->isoFormat('MMM DD, OY') }}</td>
                            <td>
                                <?php $totalFee = 0; ?>                                
                                @foreach ($appointment->services() as $service)
                                    @if ($loop->last)
                                        <?php $totalFee+= $service->price; ?>
                                        {{$service->desc}}
                                    @else
                                        {{$service->desc . ', '}}
                                    @endif
                                @endforeach
                            </td>
                            <td>&#8369;{{$totalFee}}</td>
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
                                                                Current appointment date: {{\Carbon\Carbon::parse($appointment->date)->isoFormat('MMM DD OY') }}
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
                                    <span class="text-muted">Abandoned</span>
                                        @break
                                    @default
                                        
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
        
    @endif

</div>

<script>

window.onload = () => {
    validateDate();
};

let dateInput = document.getElementById('dateInput');
let dateStatus = document.getElementById('dateStatus');
let setAppointmentButton = document.getElementById('setAppointmentButton');

let serviceSelect = document.getElementById('serviceSelect');
let costDiv = document.getElementById('costDiv');
let costLabel = document.getElementById('costLabel');
let totalfee = 0;


dateInput.addEventListener('change', () => {
    validateDate();
});

function validateDate(){

    let date = dateInput.value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/validateappointmentdate/' + date, true);

    xhr.onload = function() {

        if (this.status == 200) {                                      

            if(this.responseText == '0' || this.responseText == 0){
                
                setAppointmentButton.disabled = false;
                dateStatus.textContent = 'This date is valid for an appointment';
                dateStatus.className = 'text-success';

            } else if(this.responseText == '1' || this.responseText == 1) {
                setAppointmentButton.disabled = true;
                dateStatus.textContent = 'Sorry, max clients have been reached for this date';
                dateStatus.className = 'text-danger';
            } else if(this.responseText == '2' || this.responseText == 2) {
                setAppointmentButton.disabled = true;
                dateStatus.textContent = 'Invalid date, must be today or within a month';
                dateStatus.className = 'text-danger';
            } else if(this.responseText == '3' || this.responseText == 3) {
                setAppointmentButton.disabled = true;
                dateStatus.textContent = 'Invalid date, must be today or within a month';
                dateStatus.className = 'text-danger';            
            } else if(this.responseText == '4' || this.responseText == 4) {
                setAppointmentButton.disabled = true;
                dateStatus.textContent = 'Sorry, Clinic are not accepting appointments right now';
                dateStatus.className = 'text-danger';
            } else {
                setAppointmentButton.disabled = true;
                dateStatus.textContent = 'Invalid date';
                dateStatus.className = 'text-danger';
            }            

        }              

    }

    xhr.send()

}

serviceSelect.addEventListener('change', () => {    

    if(serviceSelect.selectedOptions.length > 0){        
    
        let ids = getSelectValues(serviceSelect).join("");        

        costDiv.classList.remove('d-none');
        
        let xhr = new XMLHttpRequest();

        xhr.open('GET', APP_URL + '/gettotalservicefee/' + ids, true);

        xhr.onload = function() {

            if (this.status == 200) {                 
                
                costLabel.textContent = this.responseText;

            }              

        }

        xhr.send()

    } else {

        costDiv.classList.add('d-none');
        costLabel.value = null;

    }

});



</script>

@endsection