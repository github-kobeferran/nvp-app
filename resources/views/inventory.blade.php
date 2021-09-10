@extends('layouts.app')

@include('inc.banner')

@section('content')

    <div class="container ">

        <div class="row">

            <div class="col text-center">

                <h1>Our Products</h1> 

            </div>

        </div>
        
        <hr>

        @if (!is_null(\App\Models\Item::first()))
        
            <div class="row">

                <div class="d-flex justify-content-center container mt-5">
                    
                    @foreach (\App\Models\Item::orderBy('quantity', 'desc')->get() as $item)

                        <div class="card p-3 bg-white mx-auto border border-secondary"><i class="fa fa-circle {{$item->quantity > 0 ? 'text-success' : 'text-secondary'}} "></i>
                            <div class="about-product text-center mt-2">
                                @if (is_null($item->image))
                                    <img src="{{url('storage/images/item/no-image-item.jpg')}}" width="100" height="100">                                    
                                @else
                                    
                                @endif
                                <div class="border-bottom">
                                    <span>{{$item->desc}}</span>
                                    <br>
                                    <span class="text-muted"  style="font-size: .8em;">{{$item->category->desc}}</span>
                                    
                                </div>
                            </div>
                            <div class="stats mt-2 border-bottom">
                                <div class="d-flex justify-content-end p-price"><span>&#8369; {{number_format($item->reg_price, 2)}}</span></div>
                            </div>
                            <div class="input-group mb-3">

                                <div class="input-group-prepend">
                                  <button onclick="decreaseQuantity(document.getElementById('quantity-{{$item->id}}'))" class="btn btn-outline-info" type="button" {{$item->quantity < 1 ? 'disabled' : ''}}>-</button>
                                </div>
                                <input id="quantity-{{$item->id}}" type="number" min="1" max="{{$item->quantity}}" class="form-control w-25 text-center" value="{{$item->quantity < 1 ? '0' : '1'}}" {{$item->quantity < 1 ? 'disabled' : ''}}>
                                <div class="input-group-append">
                                    <button onclick="increaseQuantity(document.getElementById('quantity-{{$item->id}}'), {{$item->quantity}})" class="btn btn-outline-info" type="button" {{$item->quantity < 1 ? 'disabled' : ''}}>+</button>
                                </div>

                            </div>

                            <div class="text-center">                                
                                @if ($item->quantity > 0)
                                    <span class="text-muted" style="font-size: .8em !important;">{{$item->quantity}} items left</span>
                                @else
                                    <span class="text-muted" style="font-size: .8em !important;">Out of stock</span>
                                @endif
                            </div>

                            <button class="btn btn-block btn-primary rounded-0" {{$item->quantity < 1 ? 'disabled' : ''}}>Buy now</button>
                        </div>
                        
                    @endforeach

                </div>               

            </div>

        @else 

            <div class="row">

                <div class="col text-center">

                    Sorry, no available products right now.

                </div>

            </div>

        @endif
        
        

    </div>


<script>

$(document).ready( function () {
    $('#products').DataTable();
} );

function increaseQuantity(quantityInput, max) {
    if(quantityInput.value < max)
        quantityInput.value++;
}

function decreaseQuantity(quantityInput) {
    if(quantityInput.value > 1)
        quantityInput.value--;
}

</script>
@endsection