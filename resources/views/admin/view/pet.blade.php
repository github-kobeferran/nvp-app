@extends('layouts.app')

@include('inc.banner')

@section('content')

@include('inc.sidebar')

<div class="container">

    <div class="row">

        <div class="col">
            <h1>Pets</h1>
        </div>
        <div class="col">
            <button type="button" onclick="togglePetTypes()" class="btn btn-lg btn-info text-white float-right"><i class="fa fa-caret-down" aria-hidden="true"></i> View Pet Types</button>
        </div>

    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @include('inc.messages')

    <div id="petTypePanel" class="container ml-3 mb-4 d-none">

        <hr>
            
        <div class="row">

            <div class="col border-right">

                @empty(\App\Models\PetType::first())

                    No Pet Types yet. Add one.

                @else

                    <h3 class="float-right">Pet Types</h3>

                    <div class="input-group mt-3 mb-1">
                        <div class="input-group-prepend ">
                            <span class="input-group-text bg-info text-white" id="basic-addon1"><i class="fa fa-search " aria-hidden="true"></i>
                        </span>
                        </div>
                        <input id="petTypeSearchBox" type="text" class="form-control form-control-lg" placeholder="Search.." aria-label="" aria-describedby="basic-addon1">
                    </div>

                    <div class="table-responsive" style="max-height: 300px; overflow: auto; display:inline-block;" >

                        <table class="table table-bordered border">

                            <thead class="">

                                <th class="w-50">Type</th>
                                <th class="w-50">Action</th>

                            </thead>

                            <tbody id="type-list" >

                                @foreach (\App\Models\PetType::latest()->get() as $type)

                                    <tr>

                                        <td style="font-size: large;"> {{ucfirst($type->type)}} </td>
                                        <td colspan="2" >
                                             <button type="button" onclick="editType({{$type->id}})" class="btn btn-info mx-auto text-white">Edit</button> 
                                             <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteType-{{$type->id}}">
                                                Delete
                                             </button>                                              
                                        </td>

                                    </tr>                                   
                                    
                                    <div class="modal fade" id="deleteType-{{$type->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Delete {{ucfirst($type->type)}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                            You sure you want to delete {{ucfirst($type->type)}}?
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            {!!Form::open(['url' => 'deletepettype'])!!}

                                                {{Form::hidden('id', $type->id)}}

                                                <button type="submit" class="btn btn-primary">Yes</button>

                                            {!!Form::close()!!}
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                @endforeach
                    
                            </tbody>

                        </table>

                    </div>
                    
                @endempty

            </div>           


            <div class="col text-right">

                <h3 id="form-title">Add a Pet Type</h3>

                {!!Form::open(['url' => '/storepettype', 'id' => 'typeForm', 'class' => 'mt-5 float-right'])!!}

                    <div class="form-inline">
                        <label for="type">Pet Type</label>
                        {{Form::text('type', '', ['maxlength' => '50', 'class' => 'form-control ml-2', 'required' => 'required'])}}
                    </div>

                    <br>

                    <button type="submit" class="btn border btn-primary" > <i class="fa fa-plus mr-2"  aria-hidden="true"></i>Add Type</button>

                {!!Form::close()!!}

                {!!Form::open(['url' => '/updatepettype', 'id' => 'editTypeForm', 'class' => 'mt-5 float-right d-none'])!!}

                    {{Form::hidden('id', null, ['id' => 'edit-hidden-id'])}}

                    <div class="form-inline">
                        <label for="type">Pet Type</label>
                        {{Form::text('type', '', ['maxlength' => '50', 'id' => 'edit-type-input', 'class' => 'form-control ml-2', 'required' => 'required'])}}
                    </div>

                    <br>

                    <button type="submit" class="btn border btn-primary" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update</button>
                    <button type="button" onclick="cancelEdit()" class="btn border btn-secondary" >Cancel</button>

                {!!Form::close()!!}

            </div>

        </div>
    
        

    </div>

    <hr class="text-dark">

    <div class="table-responsive" style="max-height: 800px; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;">

        <table id="pets" class="table table-bordered ">

            <thead class="bg-dark text-white">
                <tr>
                    <th>Name</th>                
                    <th>Type</th>                
                    <th>Breed</th>                
                    <th>Sex</th>                
                    <th>Date of Birth</th>                
                    <th>Weight</th>                
                    <th>Height</th>                
                    <th>Owner</th>
                    <th>Checked</th>                    
                </tr>
            </thead>
                
            @empty(\App\Models\Pet::first())

            @else

            <tbody id="pet-list" > 

                @foreach (\App\Models\Pet::latest()->get() as $pet)
                
                 <tr>                    
                    <td><a href="{{url('/pet/' . $pet->owner->user->email . '/' . $pet->name)}}">{{ucfirst($pet->name)}}</a></td>
                    <td>{{is_null($pet->type->type) ? 'N\A' : ucfirst($pet->type->type)}}</td>
                    <td>{{is_null($pet->breed) ? 'N\A' : ucfirst($pet->breed)}}</td>                    
                    <td>{{(is_null($pet->sex) ? 'N\A' : $pet->sex ) ? 'Female' : 'Male'}}</td>                    
                    <td>{{is_null($pet->dob) ? 'N\A' : \Carbon\Carbon::parse($pet->dob)->isoFormat('MMM DD, OY') . ' (' . \Carbon\Carbon::parse($pet->dob)->diff()->format('%y years, %m months and %d days') . ')'}}</td>                                        
                    <td>{{is_null($pet->weight) ? 'N\A' : $pet->weight . ' kg'}}</td>                    
                    <td>{{is_null($pet->height) ? 'N\A' : $pet->height . ' cm'}}</td>                    
                    <td><a href="{{url('/user/' . $pet->owner->user->email)}}">{{ucfirst($pet->owner->user->first_name) . ' ' . ucfirst(substr($pet->owner->user->middle_name, 0, 1)) . '. ' . ucfirst($pet->owner->user->last_name) }}</a></td>                    
                    <td>{{(is_null($pet->checked) ? 'N\A' : $pet->checked ) ? 'Yes' : 'Not Yet' }}</td>    
                 </tr>
                    
                @endforeach

            </tbody>
                
            @endempty

        </table>

    </div>
    
</div>

<script>

$(document).ready(function() {
    $('#pets').DataTable();
} );

let petTypePanel = document.getElementById('petTypePanel');
let typeList = document.getElementById('type-list');
let petTypeSearchBox = document.getElementById('petTypeSearchBox');
let typeForm = document.getElementById('typeForm');
let editTypeForm = document.getElementById('editTypeForm');
let formTitle = document.getElementById('form-title');

function togglePetTypes(){

    if(petTypePanel.classList.contains('d-none'))
        petTypePanel.classList.remove('d-none');
    else 
        petTypePanel.classList.add('d-none');

}

function searchType(){

    let txt = petTypeSearchBox.value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/pettype/search/'+ txt, true);

    xhr.onload = function() {

        if (this.status == 200) {

            let types = JSON.parse(this.responseText);

            let output = `<tbody id="type-list">`;

            for(let i in types){

                output+= `<tr>`;    

                    output+= `<td style="font-size: large;">` + capitalizeFirstLetter(types[i].type) +`</td>`;
                    output+= `<td colspan="2" >
                        <button type="button" onclick="editType(`+ types[i].id +`)" class="btn btn-info mx-auto text-white">Edit</button> 
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteType-`+ types[i].id +`">
                            Delete
                        </button>                             
                    </td>`;                                                 

                output+= `</tr>`;    
                output+= ` <div class="modal fade" id="deleteType-`+ types[i].id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Delete `+ capitalizeFirstLetter(types[i].type) +`</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        You sure you want to delete `+ capitalizeFirstLetter(types[i].type) +`?
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {!!Form::open(['url' => 'deletepettype'])!!}

                            {{Form::hidden('id', `+ types[i].id +`)}}

                            <button type="submit" class="btn btn-primary">Yes</button>

                        {!!Form::close()!!}
                        </div>
                    </div>
                    </div>
                    </div>`;    

            }

            output+= `</tbody>`;

            typeList.innerHTML = output;
        
        } 
    }    

    xhr.send();

}

function editType(id){   
        

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/petdata/'+ id, true);

    xhr.onload = function() {

        if (this.status == 200) {

            let pet = JSON.parse(this.responseText);

            if(typeof pet !== 'undefined'){

                formTitle.textContent = 'Edit Pet Type: ' + pet.type;                
                editTypeForm.classList.remove('d-none');
                typeForm.classList.add('d-none');

                let hiddenInput = document.getElementById('edit-hidden-id');
                let typeInput = document.getElementById('edit-type-input');

                hiddenInput.value = pet.id;
                typeInput.value = pet.type;

            }

        } 

    }

    xhr.send();

}

function cancelEdit(){

    formTitle.textContent = 'Add a Pet Type'; 

    let hiddenInput = document.getElementById('edit-hidden-id');
    let typeInput = document.getElementById('edit-type-input');
    
    hiddenInput.value = null;
    typeInput.value = null;

    editTypeForm.classList.add('d-none');
    typeForm.classList.remove('d-none');


}

</script>

@endsection