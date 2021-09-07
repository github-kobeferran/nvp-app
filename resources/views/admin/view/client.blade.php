@extends('layouts.app')

@include('inc.banner')

@section('content')

@include('inc.sidebar')

<div class="container">

    <h1>Clients</h1>
    
    {!!Form::open(['url' => 'registerclient', 'class' => 'border ml-2 p-4'])!!}
    
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @include('inc.messages')

        <h3>Register a Client</h3>

        <hr>

        <div class="form-inline ">
            <label for="name">Name</label>
            {{Form::text('name', '', ['placeholder' => 'Name..', 'class' => 'form-control ml-2 w-50', 'required' => 'required'])}}
        </div>

        <hr>

        <div class="form-inline ">
            <label for="email">Email</label>
            {{Form::email('email', '', ['placeholder' => 'Email..', 'class' => 'form-control ml-2 w-50', 'required' => 'required'])}}
        </div>

        <hr>

        <button type="submit" class="btn-lg btn-primary float-right">Register Client</button>

        <br>

    {!!Form::close()!!}

    <hr class="text-dark">

    <a href="/clientsexport" class="btn btn-success text-dark ml-2 float-right">Export to Excel </a>

    <div class="table-responsive" style="max-height: 800px; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;">

        <table id="clients" class="table table-bordered ">

            <thead class="bg-info text-white">
                <tr>
                    <th>Last Name</th>                
                    <th>First Name</th>                
                    <th>Middle Name</th>                
                    <th>Email</th>                
                    <th>Sex</th>                
                    <th>Date of Birth</th>                
                    <th>Address</th>                
                    <th>Contact</th>                
                    <th>Verified</th>                
                    <th>Action</th>                
                </tr>
            </thead>
                
            @empty(\App\Models\User::where('user_type', 0)->first())

            @else

            <tbody id="client-list" > 

                @foreach (\App\Models\User::where('user_type', 0)->get() as $user)
                
                 <tr>
                     <td>{{$user->last_name}}</td>
                     <td>{{$user->middle_name}}</td>
                     <td>{{$user->first_name}}</td>
                    <td><a href="/user/{{$user->email}}">{{$user->email}}</a></td>
                    <td>
                        <?php 

                            if(is_null($user->client->sex))
                                echo 'N\A';
                            else 
                                echo $user->client->sex ? 'Female' : 'Male';
                                
                        ?>
                    </td>
                    <td>
                        <?php 

                            if(is_null($user->client->dob))
                                echo 'N\A';
                            else 
                                echo  \Carbon\Carbon::parse($user->client->dob)->isoFormat('MMM DD, OY') . ' ('. \Carbon\Carbon::parse($user->client->dob)->age .' yrs)';

                        ?>
                    </td>
                    <td>
                        <?php 

                            if(is_null($user->client->address))
                                echo 'N\A';
                            else 
                                echo $user->client->address;

                        ?>
                    </td>
                    <td>
                        <?php 

                            if(is_null($user->client->contact))
                                echo 'N\A';
                            else 
                                echo $user->client->contact;

                        ?>
                    </td>
                    <td>{{is_null($user->email_verified_at) ? 'No' : 'Yes'}}</td>
                    <td><a href="{{url('/createpet/'. $user->email)}}" class="btn btn-sm btn-primary">Add a Pet</a></td>
                 </tr>
                    
                @endforeach

            </tbody>
                
            @endempty

        </table>

    </div>

</div>

<script>

$(document).ready(function() {
    $('#clients').DataTable();
} );

</script>

@endsection