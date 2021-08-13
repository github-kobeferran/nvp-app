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

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="name">Name</label>
            {{Form::text('name', $user->name, ['class' => 'form-control form-control-lg ml-2 w-75'])}}                        

        </div>

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="sex">Sex</label>
            {{Form::select('sex', [null => 'Select from options', '0' => 'Male', '1' => 'Female'], $client->sex, ['class' => 'form-control form-control-lg ml-3'])}}

        </div>

        <hr>

        <div class="form-inline my-2"">

            <label class="text-muted" for="dob">Date of Birth</label>
            {{Form::date('dob', $client->dob, ['class' => 'form-control form-control-lg ml-3'])}}

        </div>

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="contact">Contact</label>
            {{Form::text('contact', $client->contact, ['maxlength' => '15', 'class' => 'form-control ml-2 w-75'])}}                        

        </div>

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="adress">Address</label>
            {{Form::text('address', $client->address, ['maxlength' => '100', 'class' => 'form-control ml-2 w-75'])}}                        

        </div>

        <hr>

        <button class="btn btn-block btn-primary float-right mt-4">Submit</button>

    {!!Form::close()!!}

</div>
    
@endsection