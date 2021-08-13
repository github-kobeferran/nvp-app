@extends('layouts.app')

@include('inc.banner')

@section('content')
<div class="container">
    
    <img src="{{url('storage/images/system/homepage-images/pexels-snapwire-46024.jpg')}}" class="img-fluid homepage-img" alt="Responsive image">

    <h1 class="homepage-slogan">Love Your Pet. Love Your Life.</h1>    
    
    <div class="card-deck my-4 text-center">
        <div class="card mb-4 box-shadow">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal">Something your pet need? We got it!</h4>
        </div>
        <div class="card-body">            
            <ul class="list-unstyled mt-3 mb-4">

                @empty(\App\Models\ItemCategory::all())
                    
                @else

                    @foreach (\App\Models\ItemCategory::orderBy('id', 'desc')->limit(5)->get() as $category)

                        <li>{{strtoupper($category->desc)}}</li>           
                        
                    @endforeach
                              

                @endempty
            
            </ul>
            <a href="/inventory" class="btn btn-lg btn-block btn-outline-primary">See more</a>
        </div>
        </div>
        <div class="card mb-4 box-shadow">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal">Provide the care your pet needs through our Services..</h4>
        </div>
        <div class="card-body">            
            <ul class="list-unstyled mt-3 mb-4">
                @empty(\App\Models\Service::all())
                    
                @else

                    @foreach (\App\Models\Service::orderBy('created_at', 'desc')->limit(5)->get() as $services)

                        <li>{{ucfirst(strtolower($services->desc))}}</li>           
                        
                    @endforeach

                @endempty
            </ul>
            <a href="/services" class="btn btn-lg btn-block btn-primary">View Services</a>
        </div>
        </div>
       
    </div>
                  
</div>

@include('inc.footer')

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

</script>

@endsection
