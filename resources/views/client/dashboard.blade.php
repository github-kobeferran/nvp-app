@extends('layouts.app')

@include('inc.banner')

@section('content')

@include('inc.sidebar')


    <div class="container">

        <div class="text-center">

            @empty($user->client->image)

                <img class="profile-pic" src="{{url('storage/images/client/no-image.jpg')}}" alt="">

            @else

                <img class="profile-pic" src="{{url('storage/images/client/' . $user->client->image)}}" alt="">
               
            @endempty     

            <h2 class="m-2 text-secondary "> {{$user->first_name . ' ' . substr($user->middle_name, 0, 1) . '. ' . $user->last_name}} </h2>
            <em> <h5 class="m-2 text-secondary ">{{$user->email}}</h5></em>

        </div>     

       
        <div class="table-responsive-lg">

            <table class="table table-bordered table-client text-center p-5">

                <tr>

                    <th>Sex</th>                    
                    
                    @isset($user->client->sex)

                    <td>{{$user->client->sex ? 'Female' : 'Male'}}</td>  

                    
                    @else
                    
                    
                    <td>N/A</td>

                    @endempty
                    
                </tr>
                
                <tr>

                    <th>Age</th>                    
                    
                    @empty($user->client->dob)

                        <td>N/A</td>
                    
                    @else

                        <td>{{\Carbon\Carbon::parse($user->client->dob)->age}} years </td>                    

                    @endempty
                    
                </tr>

                <tr>

                    <th>Contact</th>                    
                    
                    @empty($user->client->contact)

                        <td>N/A</td>
                    
                    @else

                        <td>{{$user->client->contact}}</td>                    

                    @endempty
                    
                </tr>
                <tr>

                    <th>Address</th>                    
                    @empty($user->client->address)

                        <td>N/A</td>
                
                    @else

                        <td>{{$user->client->address}}</td>                    

                    @endempty

                </tr>            

            </table>            

        </div>

        @if (!auth()->user()->isAdmin())

            <div class="btn-group dropleft float-right">
                <button type="button" class="btn-lg btn-info text-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu ">

                    @if(is_null($user->client->image) || is_null($user->client->dob) || is_null($user->client->contact) || is_null($user->client->address) || is_null($user->client->sex))

                        <!-- Button trigger modal -->                    
                        <button type="button" class="dropdown-item border-bottom" data-toggle="modal" data-target="#updateInfoFirst">
                            Register a Pet
                        </button>                                                                              

                    @else

                        <a href="{{url('/createpet/' . $user->email)}}" class="dropdown-item border-bottom">Register a Pet</a>
                        
                    @endif
                    
                    <a href="/edituser" class="dropdown-item">Change Personal Information</a>
                
                </div>
            </div>
            
        @endif

        <div class="modal fade" id="updateInfoFirst" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    You must update your personal information first.
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                
                </div>
            </div>
            </div>
        </div>

        <br>
        <br>

        @if (auth()->user()->isAdmin())
            <div>
                <a href="{{url('transactions/' . $user->email)}}" class="btn-lg btn-success float-left">  See {{$user->first_name}}'s transactions <i class="fa fa-list-alt" aria-hidden="true"></i></a>
                <a href="{{url('createpet/' . $user->email)}}" class="btn-lg btn-primary float-right"><i class="fa fa-paw text-danger" aria-hidden="true"></i>  Add Pet to this Client</a>
            </div>
            <br>
            <br>
        @endif   
                

        @empty($user->client->pet)
            
        @else


            @if (!auth()->user()->isAdmin())
                @if(is_null($user->client->image) || is_null($user->client->dob) || is_null($user->client->contact) || is_null($user->client->address) || is_null($user->client->sex))
                    <div class="text-muted text-center">Update your Personal Information to view Pets and Transactions</div>
                @endif                
            @endif
            
            <div class="toony-text-lg text-center text-danger">

                @if (auth()->user()->isAdmin())
                    {{$user->first_name}}'s Pets
                @else
                    My Pets
                @endif

            </div>
            

            <div class="d-flex flex-wrap">

                @foreach ($user->client->pet as $pet)

                    @empty($pet->image)

                        <img data-toggle="tooltip" title="{{ucfirst($pet->name)}}" onclick="redirectToPet('{{$user->email}}', '{{$pet->name}}')" class="pets-pic" src="{{url('storage/images/pet/no-image-pet.jpg')}}" alt="">

                    @else
                    
                    <img data-toggle="tooltip" title="{{ucfirst($pet->name)}}" onclick="redirectToPet('{{$user->email}}', '{{$pet->name}}')" class="pets-pic" src="{{url('storage/images/pet/' . $pet->image)}}" alt="">

                    @endempty

                    
                @endforeach                                

            </div>

        @endempty

    </div>


<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

function redirectToPet(clientEmail, petName){

    window.location.href = APP_URL + '/pet/' + clientEmail + '/' + petName;

}

</script>

    
@endsection