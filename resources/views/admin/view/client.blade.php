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

    <div class="input-group mt-3 mb-1">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>
          </span>
        </div>
        <input id="searchBox" type="text" class="form-control form-control-lg" placeholder="Search.." aria-label="Username" aria-describedby="basic-addon1">
        <a href="/clientsexport" class="btn btn-success text-dark ml-2">Export to Excel </a>
    </div>

    <div class="table-responsive" style="max-height: 800px; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;">

        <table class="table table-bordered ">

            <thead class="bg-info text-white">
                <tr>
                    <th>Name</th>                
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
                    <td><a href="/user/{{$user->email}}">{{$user->name}}</a></td>
                    <td>{{$user->email}}</td>
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

let searchBox = document.getElementById('searchBox');
let clientList = document.getElementById('client-list');

searchBox.addEventListener('keyup', searchClient);

function searchClient(){

    let txt = searchBox.value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/clients/search/'+ txt, true);

    xhr.onload = function() {
        if (this.status == 200) {

            let users = JSON.parse(this.responseText);

            let output = `<tbody id="client-list">`;

            for(let i in users){

                output+= `<tr>`;    

                    output+= `<td><a href="/user/` + users[i].email + `">` + users[i].name +`</a></td>`;
                    output+= `<td>`+ users[i].email +`</td>`;

                    if(users[i].client.sex !== null)
                        output+= `<td>`+ (users[i].client.sex  ? 'Female' : 'Male') +`</td>`;
                    else
                        output+= `<td>N/A</td>`;

                    if(users[i].client.dob !== null)
                        output+= `<td>`+ users[i].client.dob_string +` (`+ users[i].client.age + `yrs)` +`</td>`;
                    else
                        output+= `<td>N/A</td>`;

                    if(users[i].client.address !== null)
                        output+= `<td>`+ users[i].client.address +`</td>`;
                    else
                        output+= `<td>N/A</td>`;

                    if(users[i].client.contact !== null)
                        output+= `<td>`+ users[i].client.contact +`</td>`;
                    else
                        output+= `<td>N/A</td>`;

                    if(users[i].email_verified_at !== null)                        
                        output+= `<td>Yes</td>`;
                    else
                        output+= `<td>No</td>`;

                    output+= `<td><a href="`+ APP_URL +`/createpet/` + users[i].email +`" class="btn btn-sm btn-primary">Add a Pet</a></td>`;
                    
                output+= `</tr>`;    

            }

            output+= `</tbody>`;

            clientList.innerHTML = output;
        
        } 
    }    

    xhr.send();

}

</script>

@endsection