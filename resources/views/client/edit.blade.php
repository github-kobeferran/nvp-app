@extends('layouts.app')

@include('inc.banner')

@section('content')

@include('inc.sidebar')

<div class="container mt-2">

    <h2><a class="btn btn-light" href="/user"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Profile</a>  Personal Information  </h2> 
    
    <hr>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @include('inc.messages')

    {!!Form::open(['url' => '/updateuser', 'files' => true, 'class' => 'border-2 m-2 ']) !!}

        {{Form::hidden('id', $client->id)}}

        <div class="form-inline">
            <label class="text-muted" for="image">Image</label>            
            {{Form::file('image', ['class' => 'form-control border-0  ml-3'])}}
        </div>        

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline my-2">

            <label class="text-muted" for="name">Last Name</label>
            {{Form::text('last_name', $user->last_name, ['class' => 'form-control form-control-lg ml-2 w-75', 'placeholder' => 'Last Name'])}}                        

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline my-2">

            <label class="text-muted" for="name">First Name</label>
            {{Form::text('first_name', $user->first_name, ['class' => 'form-control form-control-lg ml-2 w-75', 'placeholder' => 'First Name'])}}                        

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline my-2">

            <label class="text-muted" for="name">Middle Name</label>
            {{Form::text('middle_name', $user->middle_name, ['class' => 'form-control form-control-lg ml-2 w-75', 'placeholder' => 'Middle Name'])}}                        

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline my-2">

            <label class="text-muted" for="sex">Sex</label>
            {{Form::select('sex', [null => 'Select from options', '0' => 'Male', '1' => 'Female'], $client->sex, ['class' => 'form-control form-control-lg ml-3'])}}

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline my-2">

            <label class="text-muted" for="dob">Date of Birth</label>
            {{Form::date('dob', $client->dob, ['class' => 'form-control form-control-lg ml-3'])}}

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-inline my-2">

            <label class="text-muted" for="contact">Contact</label>
            {{Form::text('contact', $client->contact, ['maxlength' => '15', 'class' => 'form-control ml-2 w-75'])}}                        

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <div class="form-group my-2">

            <label class="text-muted" for="adress">Address <span style="font-size: .8em;" class="text-muted">: Street, Baranggay, City/Municipality, Province (seperated by comma)</span></label>
            {{Form::text('address', $client->address, ['maxlength' => '100', 'class' => 'form-control ml-2 w-100'])}}                        

        </div>

        <hr class="bg-dark" style="opacity: .25">

        <button class="btn btn-block btn-primary float-right mt-4">Submit</button>

    {!!Form::close()!!}

</div>
    
@endsection