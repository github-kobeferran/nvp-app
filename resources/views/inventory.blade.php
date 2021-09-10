@extends('layouts.app')

@include('inc.banner')

@section('content')

<?php 
    $merchant_id =  config('app.paypalMerchantID');
?>

<script>
        merchant_id = {!! json_encode($merchant_id) !!}
</script>

    <div class="container ">

        <div class="row">

            <div class="col text-center">

                <h1>Our Products</h1> 

            </div>

        </div>
        
        <hr>

        @include('inc.messages')

        @if (!is_null(\App\Models\Item::first()))
        
            <div class="row">

                <div class="d-flex justify-content-center container mt-5">
                    
                    @foreach (\App\Models\Item::orderBy('quantity', 'desc')->get() as $item)

                        <div class="card p-3 bg-white mx-auto border border-secondary"><i class="fa fa-circle {{$item->quantity > 0 ? 'text-success' : 'text-secondary'}} "></i>
                            <div class="about-product text-center mt-2">
                                @if (is_null($item->image))
                                    <img src="{{url('storage/images/item/no-image-item.jpg')}}" width="100" height="100">                                    
                                @else
                                <img src="{{url('storage/images/item/' . $item->image)}}" width="100" height="100">                                    
                                @endif
                                <div class="border-bottom">
                                    <span>{{$item->desc}}</span>
                                    <br>
                                    <span class="text-muted"  style="font-size: .5em;">{{$item->category->desc}}</span>
                                    
                                </div>
                            </div>
                            <div class="stats mt-2 border-bottom">
                                <div class="d-flex justify-content-end p-price">&#8369; <span id="priceLabel-{{$item->id}}">{{number_format($item->reg_price, 2)}}</span></div>
                            </div>

                            {{Form::hidden('item_id', $item->id, ['id' => 'item-'. $item->id])}}
                            {{Form::hidden('client_id', auth()->user()->client->id, ['id' => 'client-' . auth()->user()->client->id])}}
                            {{Form::hidden('price', $item->reg_price, ['id' => 'price-' . $item->id])}}

                            <div class="input-group mb-3">

                                <div class="input-group-prepend">
                                  <button onclick="decreaseQuantity(document.getElementById('client-{{auth()->user()->client->id}}'),
                                                                    document.getElementById('item-{{$item->id}}'),
                                                                    document.getElementById('quantity-{{$item->id}}'),                                                                                    
                                                                    document.getElementById('price-{{$item->id}}'),
                                                                    document.getElementById('priceLabel-{{$item->id}}')                                                                    
                                                                    )" class="btn btn-outline-info" type="button" {{$item->quantity < 1 ? 'disabled' : ''}}>-</button>
                                </div>
                                <input id="quantity-{{$item->id}}" oninput="render(document.getElementById('client-{{auth()->user()->client->id}}'),
                                                                                   document.getElementById('item-{{$item->id}}'),
                                                                                   document.getElementById('quantity-{{$item->id}}'),                                                                                    
                                                                                   document.getElementById('price-{{$item->id}}'),
                                                                                   document.getElementById('priceLabel-{{$item->id}}')                                                                                    
                                                                                   )" type="number" min="1" max="{{$item->quantity}}" class="form-control w-25 text-center" value="0" {{$item->quantity < 1 ? 'disabled' : ''}}>
                                <div class="input-group-append">
                                    <button onclick="increaseQuantity({{$item->quantity}}, 
                                                                      document.getElementById('client-{{auth()->user()->client->id}}'),
                                                                      document.getElementById('item-{{$item->id}}'),
                                                                      document.getElementById('quantity-{{$item->id}}'),                                                                                    
                                                                      document.getElementById('price-{{$item->id}}'),
                                                                      document.getElementById('priceLabel-{{$item->id}}') 
                                                                      )" class="btn btn-outline-info" type="button" {{$item->quantity < 1 ? 'disabled' : ''}}>+</button>
                                </div>

                            </div>

                            <div class="text-center">                                
                                @if ($item->quantity > 0)
                                    <span class="text-muted" style="font-size: .8em !important;">{{$item->quantity}} items left</span>
                                @else
                                    <span class="text-muted" style="font-size: .8em !important;">Out of stock</span>
                                @endif
                            </div>

                            @if (!auth()->user()->isAdmin())
                                <button type="button" data-toggle="modal" data-target="#paypal" class="btn btn-block btn-primary rounded-0" {{$item->quantity < 1 ? 'disabled' : ''}}>Buy now</button>                                
                            @endif                           

                        </div>                        
                        
                    @endforeach

                    <script
                        src="https://www.paypal.com/sdk/js?client-id={{config('app.paypalClientID')}}&currency=PHP">
                    </script>   
                

                    <div class="modal fade" id="payPal" tabindex="-1" role="dialog" aria-labelledby="payPalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Buy Item</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                
                                <div class="modal-body">                                      
                                    <div id="paypal-button-container"></div>
                                </div>
                            
                            </div>
                        </div>
                    </div>

                    <script>
                        function render(clientID, itemID, quantity, price, priceLabel){
                        
                            document.getElementById('paypal-button-container').innerHTML = '';

                            totalPrice = quantity.value * price.value;                            

                            paypal.Buttons({
                                createOrder: function(data, actions) {                                                                
                                return actions.order.create({
                                    locale: 'en_US',                               
                                    purchase_units: [{                                                    
                                        amount: {
                                            value: totalPrice,
                                            currency_code: "PHP", 
                                            payee : merchant_id     
                                        }
                                    }]
                                });

                                },
                                onApprove: function(data, actions) {
                                    return actions.order.capture().then(function(details) {                                    
                                        window.location.replace('/makeorderclient/' + clientID.value + '/' + itemID.value + '/' + quantity.value);
                                    });
                                },
                                onCancel: function(data){                            
                                                                                            

                                }
                            }).render('#paypal-button-container');  
                        }

                    </script>

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

function increaseQuantity(max, clientID, itemID, quantity, price, priceLabel) {
    if(quantity.value < max)
           quantity.value++;    

    priceLabel.textContent = (quantity.value * price.value).toFixed(2);

    render(clientID, itemID, quantity, price, priceLabel);

}

function decreaseQuantity(clientID, itemID, quantity, price, priceLabel) {
    if(quantity.value > 1)
        quantity.value--;

    priceLabel.textContent = (quantity.value * price.value).toFixed(2);

    render(clientID, itemID, quantity, price, priceLabel);
}

</script>
@endsection