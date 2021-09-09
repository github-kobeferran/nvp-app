@extends('layouts.app')

@include('inc.banner')

@section('content')

<div class="container ">
    
    <div class="row">

        <div class="col">

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
    
                <div class="form-inline">
                    <label for="image">Pet Image</label>
                    {{Form::file('image', ['class' => 'form-control border-0 ml-2'])}}
                </div>

                <hr class="bg-dark" style="opacity: .25">

                <div class="form-inline">
                    <label for="name">Pet Name</label>
                    {{Form::text('name', '', ['placeholder' => 'Pet Name', 'class' => 'form-control ml-2', 'required' => 'required'])}}
                </div>        
    
                <hr class="bg-dark" style="opacity: .25">

                <div class="form-inline ">
                    <label for="type">Pet Type</label>
    
                    <?php                     
                        $pet_types = \App\Models\PetType::orderBy('type', 'asc')->pluck('type', 'id');                    
                    ?>
                    {{Form::select('type_id', $pet_types , null, ['title' => 'Choose Pet Type', 'id' => 'petTypeSelect', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;'])}}
                </div>          
    
                <hr class="bg-dark" style="opacity: .25">

                <div class="form-inline ">
    
                    <label for="breed">Breed</label>   
                                        
                        <div class="input-group ml-2">
    
                          <select id="breedSelect" title="Choose Breed" data-live-search="true" class="form-control selectpicker border" name="breed">
    
                          </select>
                          <input class="form-control mx-1"  type="checkbox" name="otherBreed" id="otherBreedCheck" >
                          <label for="" style="font-size: .8em;" class="text-muted mr-2">other</label>
                          <input type="text" class="form-control rounded w-25" placeholder="specify breed" name="breedText" id="breedTextInput" disabled required>
    
                        </div>
    
                </div>

                <hr class="bg-dark" style="opacity: .25">
    
                <div class="form-inline ">
                    <label for="color">Color</label>
                    
                    <div class="input-group">

                        <select name="color" title="Choose Color" id="colorSelect" data-live-search="true" class="selectpicker form-control ml-2 border">
                            <option value="Brown" data-content="<span style='background-color: #BB8434;' class='badge text-white'>Brown</span>">Brown</option>                                            
                            <option value="Red" data-content="<span style='background-color: #9D5322;' class='badge text-white'>Red</span>">Red</option>                        
                            <option value="Black" data-content="<span style='background-color: #000000;' class='badge text-white'>Black</span>">Black</option>                        
                            <option value="White" data-content="<span style='background-color: #FFFFFF;' class='badge text-dark'>White</span>">White</option>                        
                            <option value="Gold" data-content="<span style='background-color: #FCD399;' class='badge text-dark'>Gold</span>">Gold</option>                        
                            <option value="Yellow" data-content="<span style='background-color: #FCD0AB;' class='badge text-dark'>Yellow</span>">Yellow</option>                        
                            <option value="Cream" data-content="<span style='background-color: #C3A88B;' class='badge text-white'>Cream</span>">Cream</option>                        
                            <option value="Blue" data-content="<span style='background-color: #009ACD;' class='badge text-white'>Blue</span>">Blue</option>                        
                            <option value="Grey" data-content="<span style='background-color: #7B7B7C;' class='badge text-white'>Grey</span>">Grey</option>                        
                        </select>

                        <input class="form-control mx-1"  st type="checkbox" name="otherColor" id="otherColorCheck" >
                        <label for="" style="font-size: .8em;" class="text-muted mr-2">other</label>
                        <input type="text" class="form-control rounded w-25" placeholder="specify color" name="colorText" id="colorTextInput" disabled required>


                    </div>

                      
                </div>
    
                <hr class="bg-dark" style="opacity: .25">

                <div class="form-inline ">
                    <label for="dob">Date of Birth</label>                
                    {{Form::date('dob', null, ['class' => 'form-control ml-2', 'required' => 'required'])}}
                </div>

                <hr class="bg-dark" style="opacity: .25">
    
                <div class="form-inline">                
                    {{Form::radio('sex', '0', true, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])}}
                    <label for="sex" class="mx-2">Male</label>                
                    {{Form::radio('sex', '1', false, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])}}
                    <label for="sex" class="mx-2">Female</label>                
                </div>

                <hr class="bg-dark" style="opacity: .25">
    
                <div class="form-inline">
                    <label for="weight">Weight (kg)</label>
                    {{Form::number('weight', '', ['placeholder' => 'Pet Weight', 'class' => 'form-control form-control-lg mx-2', 'step' => '.01', 'min' => '.01', 'required' => 'required'])}}
                    
                </div>

                <hr class="bg-dark" style="opacity: .25">
    
                <div class="form-inline">
                    <label for="weight">Height (cm)</label>
                    {{Form::number('height', '', ['placeholder' => 'Pet Height', 'class' => 'form-control form-control-lg mx-2', 'min' => '1', 'required' => 'required'])}}
                    
                </div>

                <hr class="bg-dark" style="opacity: .25">
    
                @if (auth()->user()->isAdmin())
    
                    <div class="form-inline">
                        <label for="weight">Have been in Clinic?</label>
                        {{Form::hidden('checked', 0)}}
                        {{Form::checkbox('checked', 1, true, ['class' => ' ml-2', 'style' => 'width: 25px; height: 25px;'])}}
                    </div>
                    
                @endif
    
                <button type="submit" class="btn btn-primary btn-lg float-right">Submit</button>            
                <br>
    
            {!! Form::close() !!}
            
        @endempty
                     

        </div>

    </div>
         
</div>

<script>

window.onload = function() {
    getBreeds(petTypeSelect.selectedOptions[0].label.toLowerCase());
};

let petTypeSelect = document.getElementById('petTypeSelect');

let breedSelect = document.getElementById('breedSelect');
let otherBreedCheck = document.getElementById('otherBreedCheck');
let breedTextInput = document.getElementById('breedTextInput');

let colorSelect = document.getElementById('colorSelect');
let otherColorCheck = document.getElementById('otherColorCheck');
let colorTextInput = document.getElementById('colorTextInput');

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

        otherBreedCheck.checked = false;
        enableSpecifyBreed();

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

        otherBreedCheck.checked = false;
        enableSpecifyBreed();

        break;     

    default: 

        otherBreedCheck.checked = true;
        enableSpecifyBreed();
        break;
    }    

}

otherBreedCheck.addEventListener('change', () => {
    enableSpecifyBreed();      
});

function enableSpecifyBreed(){

    if(otherBreedCheck.checked){
        breedSelect.disabled = true;  
        breedSelect.value = null;  
        breedTextInput.disabled = false;
        breedSelect.required = false;
        breedTextInput.required = true;
    } else {
        breedSelect.disabled = false;  
        breedTextInput.disabled = true;
        breedTextInput.value = null;
        breedSelect.value = null;
        breedSelect.required = true;
        breedTextInput.required = false;
    }

    $('.selectpicker').selectpicker('refresh');

}

otherColorCheck.addEventListener('change', () => {
    enableSpecifyColor();      
});

function enableSpecifyColor(){

    if(otherColorCheck.checked){
        colorSelect.disabled = true;  
        colorSelect.value = null;  
        colorTextInput.disabled = false;
        colorSelect.required = false;
        breedTextInput.required = true;
    } else {
        colorSelect.disabled = false;  
        colorSelect.value = null;  
        colorTextInput.disabled = true;
        colorTextInput.value = null;
        colorSelect.required = true;
        colorTextInput.required = false;
    }

    $('.selectpicker').selectpicker('refresh');

}



</script>

@endsection