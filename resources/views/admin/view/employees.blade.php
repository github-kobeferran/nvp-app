@extends('layouts.app')

@include('inc.banner')

@section('content')

@include('inc.sidebar')

<div class="container">

    <h1>Employees</h1>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @include('inc.messages')
    
    {!!Form::open(['url' => 'employeestore', 'class' => 'border ml-2 p-4'])!!}          

        <h3>Add an Employee</h3>

        <hr>

        <div class="form-inline ">
            <label for="name">Name</label>
            {{Form::text('name', '', ['placeholder' => 'Name..', 'class' => 'form-control ml-2 w-50', 'required' => 'required'])}}
        </div>

        <hr>

        <div class="form-inline ">
            <label for="dob">Date of Birth</label>                
            {{Form::date('dob', null, ['class' => 'form-control ml-2', 'required' => 'required'])}}
        </div>

        <hr>

        <div class="form-inline mb-4">                
            {{Form::radio('sex', '0', true, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])}}
            <label for="sex" class="mx-2">Male</label>                
            {{Form::radio('sex', '1', false, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])}}
            <label for="sex" class="mx-2">Female</label>                
        </div>

        <hr>

        <div class="form-inline ">

            <label class="text-muted" for="contact">Contact</label>
            {{Form::text('contact', '', ['maxlength' => '15', 'class' => 'form-control ml-2 w-75'])}}                        

        </div>

        <hr>

        <div class="form-inline ">

            <label class="text-muted" for="adress">Address</label>
            {{Form::text('address', '', ['maxlength' => '100', 'class' => 'form-control ml-2 w-75'])}}                        

        </div>

        <hr>

        <button type="submit" class="btn-lg btn-primary float-right">Add Employee</button>

        <br>

    {!!Form::close()!!}

    <hr class="text-dark">

    <div class="input-group mt-3 mb-1">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>
          </span>
        </div>
        <input id="searchBox" type="text" class="form-control form-control-lg" placeholder="Search.." aria-label="Username" aria-nameribedby="basic-addon1">
    </div>

    <div  class="table-responsive" style="max-height: 800px; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;">

        <table id="employees" class="table table-bordered ">

            <thead class="bg-warning text-dark">
                <tr>
                    <th>Name</th>                
                    <th>Sex</th>                
                    <th>Date of Birth</th>                
                    <th>Address</th>                
                    <th>Contact</th>                
                    <th style="width: 20px;">Action</th>                
                </tr>
            </thead>
                
            @empty(\App\Models\Employee::first())

            @else

            <tbody id="employee-list" > 

                @foreach (\App\Models\Employee::orderBy('name', 'asc')->get() as $employee)
                
                 <tr>
                    
                    <td>{{ucfirst($employee->name)}}</td>
                    <td>{{$employee->sex == 0 ? 'Male' : 'Female'}}</td>
                    <td>{{\Carbon\Carbon::parse($employee->dob)->isoFormat('MMM DD, OY') . ' (' . \Carbon\Carbon::parse($employee->dob)->age . ' yrs)'}}</td>
                    <td>{{$employee->address}}</td>
                    <td>{{$employee->contact}}</td>
                    <td colspan="2">

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editEmployee-{{$employee->id}}">
                            Edit
                            </button> 
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteEmployee-{{$employee->id}}">
                            Delete
                        </button>

                    </td>
                 </tr>

                 
                 <div class="modal fade"  id="editEmployee-{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit <b>{{ucfirst($employee->name)}}</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        {!!Form::open(['url' => '/employeeupdate'])!!}
                        <div class="modal-body">

                            {{Form::hidden('id', $employee->id)}}
                            <div class="form-inline ">
                                <label for="name">Name</label>
                                {{Form::text('name', $employee->name, ['placeholder' => 'Name..', 'class' => 'form-control ml-2 w-50', 'required' => 'required'])}}
                            </div>
                    
                            <hr>
                    
                            <div class="form-inline ">
                                <label for="dob">Date of Birth</label>                
                                {{Form::date('dob', $employee->dob, ['class' => 'form-control ml-2', 'required' => 'required'])}}
                            </div>
                    
                            <hr>
                    
                            @if ($employee->sex == 0)

                            {{Form::radio('sex', '0', true, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])}}
                            <label for="sex" class="mx-2">Male</label>                
                            {{Form::radio('sex', '1', false, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])}}
                            <label for="sex" class="mx-2">Female</label>  
                            
                        @else
            
                            {{Form::radio('sex', '0', false, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])}}
                            <label for="sex" class="mx-2">Male</label>                
                            {{Form::radio('sex', '1', true, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])}}
                            <label for="sex" class="mx-2">Female</label>  
                            
                        @endif
                        
                    
                            <hr>
                    
                            <div class="form-inline ">
                    
                                <label class="text-muted" for="contact">Contact</label>
                                {{Form::text('contact', $employee->contact, ['maxlength' => '15', 'class' => 'form-control ml-2 w-75'])}}                        
                    
                            </div>
                    
                            <hr>
                    
                            <div class="form-inline ">
                    
                                <label class="text-muted" for="adress">Address</label>
                                {{Form::text('address', $employee->address, ['maxlength' => '100', 'class' => 'form-control ml-2 w-75'])}}                        
                    
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

                <div class="modal fade"  id="deleteEmployee-{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Delete <b>{{ucfirst($employee->name)}}</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        You sure you want to delete employee: <b>{{ucfirst($employee->name)}}</b>?
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {!!Form::open(['url' => '/employeedelete'])!!}

                            {{Form::hidden('id', $employee->id)}}

                            <button type="submit" class="btn btn-primary">Yes</button>

                        {!!Form::close()!!}
                        </div>
                    </div>
                    </div>
                </div>
                    
                @endforeach

            </tbody>
                
            @endempty

        </table>

    </div>

</div>

<script>

$(document).ready( function () {  
    $('#employees').DataTable();
} );

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