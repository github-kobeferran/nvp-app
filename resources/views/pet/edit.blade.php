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

        <div class="form-inline mb-4">
            <label for="name">Pet Name</label>
            {{Form::text('name', $pet->name, ['class' => 'form-control ml-2', 'required' => 'required'])}}
        </div>        

        <div class="form-inline mb-4">
            <label for="type">Pet Type</label>

            <?php                     
                $pet_types = \App\Models\PetType::orderBy('type', 'asc')->pluck('type', 'id');                    
            ?>

            {{Form::select('type_id', $pet_types , $pet->type->id, ['data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;'])}}
        </div>          

        <div class="form-inline mb-4">
            <label for="breed">Breed</label>                
            {{Form::text('breed', $pet->breed, ['class' => 'form-control ml-2', 'required' => 'required'])}}
        </div>

        <div class="form-inline mb-4">
            <label for="color">Color</label>                
            {{Form::text('color', $pet->color, ['maxlength' => '50', 'class' => 'form-control ml-2', 'required' => 'required'])}}
        </div>

        <div class="form-inline mb-4">
            <label for="dob">Date of Birth</label>                
            {{Form::date('dob', $pet->dob, ['class' => 'form-control ml-2', 'required' => 'required'])}}
        </div>

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

        <div class="form-inline mb-4">
            <label for="weight">Weight (kg)</label>
            {{Form::number('height', $pet->weight, ['class' => 'form-control form-control-lg mx-2', 'step' => '.01', 'min' => '.01', 'required' => 'required'])}}
            
        </div>

        <div class="form-inline mb-4">
            <label for="weight">Height (cm)</label>
            {{Form::number('weight', $pet->height, ['class' => 'form-control form-control-lg mx-2', 'min' => '1', 'required' => 'required'])}}
            
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

</div>

<script>


</script>

@endsection