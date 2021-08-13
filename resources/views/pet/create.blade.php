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
        <em><p class="mb-4 text-center text-muted">Owner: <a href="{{url('/user/' . $user->email)}}" style="text-decoration: none; color: inherit;">{{$user->name}}</a></p></em>

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
                {{Form::select('type_id', $pet_types , null, ['data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;'])}}
            </div>          

            <div class="form-inline mb-4">
                <label for="breed">Breed</label>                
                {{Form::text('breed', '', ['class' => 'form-control ml-2', 'required' => 'required'])}}
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
        

    {{-- @if (!auth()->user()->isAdmin())

        {!!Form::open(['url' => '/registerpetclient', 'files' => true, 'id' => 'petForm', 'class' => 'p-4']) !!}

        client

        {!! Form::close() !!}
        
    @else

        {!!Form::open(['url' => '/registerpetadmin', 'files' => true, 'id' => 'petForm', 'class' => 'p-4']) !!}

        admin

        {!! Form::close() !!}
        
    @endif     --}}
    
    
        

</div>

<script>


</script>

@endsection