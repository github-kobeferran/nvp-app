@extends('layouts.app')

@include('inc.banner')

@section('content')

<div class="container ">

    <h2 class="text-center">Edit {{$pet->name}}'s Details</h2>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @include('inc.messages')
    

    {!!Form::open(['url' => '/updatepet', 'files' => true, 'id' => 'petForm', 'class' => 'p-4']) !!}

        {{Form::hidden('id', $pet->id)}}

        <div class="form-check mb-4">
            @empty($pet->image)
            
            <img src="{{url('storage/images/pet/no-image-pet.jpg')}}" alt="" style="width: 200px; height: 200px;">
            
            @else
            
            <img src="{{url('storage/images/pet/' . $pet->image)}}" alt="" style="width: 200px; height: 200px;">
            
            @endempty
            <label class="" for="image">Change Image</label>            
            {{Form::file('image', ['class' => ' border-0 ml-2 mt-2'])}}
        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline mb-4">
            <label for="name">Pet Name</label>
            {{Form::text('name', $pet->name, ['class' => 'form-control ml-2', 'required' => 'required'])}}
        </div>        

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline mb-4">
            <label for="type">Pet Type</label>

            <?php                     
                $pet_types = \App\Models\PetType::orderBy('type', 'asc')->pluck('type', 'id');                    
            ?>

            {{Form::select('type_id', $pet_types , $pet->type->id, ['id' => 'petTypeSelect', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;'])}}
        </div>          
        
        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline ">
    
            <label for="breed">Breed</label>   

            <span id="breedLabel" class="text-muted ml-2">{{$pet->breed}}</span>    
            
            
            {{Form::hidden('breed', $pet->breed, ['id' => 'breed'])}}

            <button type="button" id="breedButton" class="btn btn-sm btn-info rounded-0 mx-1 mx-2">Edit</button>                
            {{Form::hidden('asdf', $pet->breed, ['id' => 'oldbreed'])}}

            <div id="editBreedPanel" class="input-group m-2 d-none">

                <select id="breedSelect" title="Choose Breed" data-live-search="true" class="form-control selectpicker border">

                </select>
                <input class="form-control mx-1"  type="checkbox" id="otherBreedCheck" >
                <label for="" style="font-size: .8em;" class="text-muted mr-2">other</label>
                <input type="text" class="form-control rounded w-25" placeholder="specify breed" minlength="2" maxlength="15" id="breedTextInput" disabled>
            </div>

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline ">
            <label for="color">Color</label>

            <span name="color" id="colorLabel" value="{{$pet->color}}" class="text-muted ml-2">{{$pet->color}}</span>            
            {{Form::hidden('color', $pet->color, ['id' => 'color'])}}

            <button type="button" id="colorButton" class="btn btn-sm btn-info rounded-0 mx-1 mx-2">Edit</button>                
            {{Form::hidden('asdf', $pet->color, ['id' => 'oldcolor'])}}
            
            <div id="editColorPanel" class="input-group m-2 d-none">

                <select title="Choose Color" id="colorSelect" data-live-search="true" class="selectpicker form-control ml-2 border">
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

                <input class="form-control mx-1"  type="checkbox" id="otherColorCheck" >
                <label for="" style="font-size: .8em;" class="text-muted mr-2">other</label>
                <input type="text" class="form-control rounded w-25" placeholder="specify color" id="colorTextInput" disabled>


            </div>

              
        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline mb-4">
            <label for="dob">Date of Birth</label>                
            {{Form::date('dob', $pet->dob, ['class' => 'form-control ml-2', 'required' => 'required'])}}
        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline mb-4">    

            @if ($pet->sex == 0)

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
            
                          
        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline mb-4">
            <label for="weight">Weight (kg)</label>
            {{Form::number('weight', $pet->weight, ['class' => 'form-control form-control-lg mx-2', 'step' => '.01', 'min' => '.01', 'required' => 'required'])}}
            
        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline mb-4">
            <label for="weight">Height (cm)</label>
            {{Form::number('height', $pet->height, ['class' => 'form-control form-control-lg mx-2', 'min' => '1', 'required' => 'required'])}}
            
        </div>

        <hr class="bg-dark" style="opacity: .25">

        @if (auth()->user()->isAdmin())

            <div class="form-inline mb-4">
                <label for="weight">Have been in Clinic?</label>
                {{Form::hidden('checked', 0)}}
                {{Form::checkbox('checked', 1, true, ['class' => ' ml-2', 'style' => 'width: 25px; height: 25px;'])}}
            </div>
            
        @endif
        
        



        <button type="submit" class="btn btn-primary btn-lg float-right">Submit</button>            
        <br>

    {!! Form::close() !!}

</div>

<script>

window.onload = function() {
    getBreeds(petTypeSelect.selectedOptions[0].label.toLowerCase());
};


let breed = document.getElementById('breed');
let color = document.getElementById('color');

let breedButton = document.getElementById('breedButton');
let editBreedPanel = document.getElementById('editBreedPanel');
let breedLabel = document.getElementById('breedLabel');
let oldbreed = document.getElementById('oldbreed');

let colorButton = document.getElementById('colorButton');
let editColorPanel = document.getElementById('editColorPanel');
let colorLabel = document.getElementById('colorLabel');
let oldcolor = document.getElementById('oldcolor');

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


breedButton.addEventListener('click', () => {
 
    if(editBreedPanel.classList.contains('d-none')){

        editBreedPanel.classList.remove('d-none');
        breedButton.className = 'btn btn-sm btn-secondary rounded-0 mx-1 mx-2';
        breedButton.textContent = 'Cancel';

    } else {

        editBreedPanel.classList.add('d-none');
        breedButton.className = 'btn btn-sm btn-info rounded-0 mx-1 mx-2';
        breedButton.textContent = 'Edit';
        breedLabel.textContent = oldbreed.value;
        breed.value = oldbreed.value;


    }
 
});

colorButton.addEventListener('click', () => {
 
    if(editColorPanel.classList.contains('d-none')){

        editColorPanel.classList.remove('d-none');
        colorButton.className = 'btn btn-sm btn-secondary rounded-0 mx-1 mx-2';
        colorButton.textContent = 'Cancel';

    } else {

        editColorPanel.classList.add('d-none');
        colorButton.className = 'btn btn-sm btn-info rounded-0 mx-1 mx-2';
        colorButton.textContent = 'Edit';
        colorLabel.textContent = oldcolor.value;
        color.value = oldcolor.value;

    }
 
});

breedSelect.addEventListener('change', () => {

    breedLabel.textContent = breedSelect.value;
    breed.value = breedSelect.value;    

});

colorSelect.addEventListener('change', () => {

    colorLabel.textContent = colorSelect.value;
    color.value = colorSelect.value;    

});

breedTextInput.addEventListener('keyup', () => {

    breedLabel.textContent = breedTextInput.value;
    breed.value = breedTextInput.value;    
    

});

colorTextInput.addEventListener('keyup', () => {

    colorLabel.textContent = colorTextInput.value;
    color.value = colorTextInput.value;    
    

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

        if(!editBreedPanel.classList.contains('d-none')){
            breedSelect.required = false;
            breedTextInput.required = true; 
        }
                   
        breedLabel.textContent = oldbreed.value;
        breed.value = oldbreed.value;
    } else {
        breedSelect.disabled = false;  
        breedTextInput.disabled = true;
        breedTextInput.value = null;

        if(!editBreedPanel.classList.contains('d-none')){
            breedSelect.required = true;
            breedTextInput.required = false;
        }

        breedLabel.textContent = oldbreed.value;
        breed.value = oldbreed.value;
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
        
        if(!editColorPanel.classList.contains('d-none')){
            colorSelect.required = false;
            colorTextInput.required = true;  
        }

        colorLabel.textContent = oldcolor.value;
        color.value = oldcolor.value;
    } else {
        colorSelect.disabled = false;  
        colorSelect.value = null;  
        colorTextInput.disabled = true;
        colorTextInput.value = null;
        if(!editColorPanel.classList.contains('d-none')){
            colorSelect.required = false;
            colorTextInput.required = true;  
        }
        colorLabel.textContent = oldcolor.value;
        color.value = oldcolor.value;
    }

    $('.selectpicker').selectpicker('refresh');

}


</script>

@endsection