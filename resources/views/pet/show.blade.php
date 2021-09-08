@extends('layouts.app')

@include('inc.banner')

@section('content')

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

                <td> {{$pet->checked ? 'Have visited the Clinic' : 'Have not visited the Clinic'}}  </td>

            </tr>

            @if (!auth()->user()->isAdmin())
                
                <tr>

                    <td><a href="{{url('/editpet/' . $pet->name)}}"> Edit {{$pet->name}}'s Details</a></td>

                </tr>

            @endif
                                

        </table>

    </div>

    <hr>

    {{-- <h3>{{ucfirst($pet->name)}}'s transaction history  <i class="fa fa-history" aria-hidden="true"></i></h3>

    <hr>

    <div class="table-responsive-lg">

        <table class="table table-bordered table-client text-center p-5">

            <thead>

                <th></th>
                <th></th>
                <th></th>
                <th></th>

            </thead>
                                          

        </table>

    </div> --}}

</div>

@endsection