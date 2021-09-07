@extends('layouts.app')

@include('inc.banner')

@section('content')

<div class="container ">

    @empty(\App\Models\PetType::first())

        @if (auth()->user()->isAdmin())
        
            <h3 class="text-center">Add a Pet Type First!</h3>

        @else

            <h3 class="text-center">Can't add pets right now.</h3>
            
        @endif

    @else

        <h2 class="text-center">Pet Registration</h2>
        <em><p class="mb-4 text-center text-muted">Owner: <a href="{{url('/user/' . $user->email)}}" style="text-decoration: none; color: inherit;">{{$user->first_name . ' ' . $user->last_name}}</a></p></em>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @include('inc.messages')
    

        {!!Form::open(['url' => '/registerpetadmin', 'files' => true, 'id' => 'petForm', 'class' => 'p-4']) !!}

            {{Form::hidden('client_id', $user->client->id)}}

            <div class="form-inline mb-4">
                <label for="image">Pet Image</label>
                {{Form::file('image', ['class' => 'form-control border-0 ml-2'])}}
            </div>

            <div class="form-inline mb-4">
                <label for="name">Pet Name</label>
                {{Form::text('name', '', ['class' => 'form-control ml-2', 'required' => 'required'])}}
            </div>        

            <div class="form-inline mb-4">
                <label for="type">Pet Type</label>

                <?php                     
                    $pet_types = \App\Models\PetType::orderBy('type', 'asc')->pluck('type', 'id');                    
                ?>
                {{Form::select('type_id', $pet_types , null, ['id' => 'petTypeSelect', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;'])}}
            </div>          

            <div class="form-inline mb-4">

                <label for="breed">Breed</label>   
                                    
                    <div class="input-group ml-2">

                      <select id="breedSelect" data-live-search="true" class="form-control selectpicker border" name="">

                      </select>
                      <input class="form-control"  type="checkbox" name="" id="othersCheck" >
                      <label for="" class="text-muted mr-2">others</label>
                      <input type="text" class="form-control" placeholder="specify" name="nuevaFactura" id="breedTextInput" disabled required>

                    </div>

            </div>

            <div class="form-inline mb-4">
                <label for="color">Color</label>                
                {{Form::text('color', '', ['maxlength' => '50', 'class' => 'form-control ml-2', 'required' => 'required'])}}
            </div>

            <div class="form-inline mb-4">
                <label for="dob">Date of Birth</label>                
                {{Form::date('dob', null, ['class' => 'form-control ml-2', 'required' => 'required'])}}
            </div>

            <div class="form-inline mb-4">                
                {{Form::radio('sex', '0', true, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])}}
                <label for="sex" class="mx-2">Male</label>                
                {{Form::radio('sex', '1', false, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])}}
                <label for="sex" class="mx-2">Female</label>                
            </div>

            <div class="form-inline mb-4">
                <label for="weight">Weight (kg)</label>
                {{Form::number('height', '', ['class' => 'form-control form-control-lg mx-2', 'step' => '.01', 'min' => '.01', 'required' => 'required'])}}
                
            </div>

            <div class="form-inline mb-4">
                <label for="weight">Height (cm)</label>
                {{Form::number('weight', '', ['class' => 'form-control form-control-lg mx-2', 'min' => '1', 'required' => 'required'])}}
                
            </div>

            @if (auth()->user()->isAdmin())

                <div class="form-inline mb-4">
                    <label for="weight">Have been in Clinic?</label>
                    {{Form::checkbox('checked', 1, true, ['class' => ' ml-2', 'style' => 'width: 25px; height: 25px;'])}}
                    {{Form::hidden('checked', 0)}}
                </div>
                
            @endif

            <button type="submit" class="btn btn-primary btn-lg float-right">Submit</button>            
            <br>

        {!! Form::close() !!}
        
    @endempty
                         
</div>

<script>

window.onload = function() {
    getBreeds(petTypeSelect.selectedOptions[0].label.toLowerCase());
};

let petTypeSelect = document.getElementById('petTypeSelect');
let breedSelect = document.getElementById('breedSelect');
let othersCheck = document.getElementById('othersCheck');
let breedTextInput = document.getElementById('breedTextInput');

petTypeSelect.addEventListener('change', () => {    

    getBreeds(petTypeSelect.selectedOptions[0].label.toLowerCase());
    

});

function getBreeds(type){        

    let xhr = new XMLHttpRequest();

    for(i = 0; i < breedSelect.length; i++){
        breedSelect.remove(i);
    }

    switch(type){
        case 'dog':     

        xhr.open('GET', 'https://dog.ceo/api/breeds/list/all', true);
                
        xhr.onload = function() {

            if (this.status == 200) { 

                let breeds = JSON.parse(this.responseText); 
                
                let breedNames = Object.keys(breeds.message);
                           
                for(let i in breedNames){ 

                    if(typeof breedNames[i] !== null){                                                    
                        breedSelect.options[i] = new Option(breedNames[i], breedNames[i]); 
                    }

                }
                
                $('.selectpicker').selectpicker('refresh');
                                
            }

        }

        xhr.send(); 

        break;
        case 'cat':      

        xhr.open('GET', 'https://api.thecatapi.com/v1/breeds', true);
                
                xhr.onload = function() {
        
                    if (this.status == 200) { 
        
                        let breeds = JSON.parse(this.responseText); 

                        for(let i in breeds){ 
        
                            if(typeof breeds[i] !== null){                                                    
                                breedSelect.options[i] = new Option(breeds[i].name, breeds[i].name); 
                            }
        
                        }
                        
                        $('.selectpicker').selectpicker('refresh');
                                        
                    }
        
                }
        
                xhr.send(); 

        break;
        case 'bird':

        break;
        case 'pig':

        break;

    default: 
        
        break;
    }    

}

othersCheck.addEventListener('change', () => {

    if(othersCheck.checked){
        breedSelect.disabled = true;
        
    }

});

</script>

@endsection