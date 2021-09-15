@extends('layouts.app')

@include('inc.banner')

@section('content')

<?php 
    $merchant_id =  config('app.paypalMerchantID');

?>

<script>
        var merchant_id = {!! json_encode($merchant_id) !!}
</script>

    <div class="container ">

        <div class="row">

            <div class="col text-center">

                <h1>Our Products</h1> 
                              

            </div>

        </div>
        <div class="row">

            <div class="col">

                @if (\App\Models\Setting::first()->stop_orders)

                    <span class="text-danger float-left">*Clinic is not accepting orders at the moment</span>
                    
                @endif

                <div>
                    <div class="dropdown float-right ">
                        <a class="btn btn-light border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @switch($sortBy)
                                @case('asc')
                                    Ascending
                                    @break
                                @case('desc')
                                    Descending
                                    @break                              
                            @default
                                
                        @endswitch
                        </a>
                      
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                          <a class="dropdown-item" {{$sortBy == 'asc' ? 'active' : ''}} href="{{url('/inventory/' . $orderBy . '/asc')}}">Ascending</a>
                          <a class="dropdown-item" {{$sortBy == 'desc' ? 'active' : ''}} href="{{url('/inventory/' . $orderBy . '/desc')}}">Descending</a>                          
                        </div>
                      </div>    
                </div>

                <div>
                    <div class="dropdown float-right mr-2">
                        <a  class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          @switch($orderBy)
                              @case('quantity')
                                  Quantity
                                  @break
                              @case('desc')
                                  Description
                                  @break
                              @case('reg_price')
                                  Price
                                  @break
                              @case('created_at')
                                  Newest
                                  @break
                              @default
                                  
                          @endswitch
                        </a>
                      
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                          <a class="dropdown-item" {{$orderBy == 'quantity' ? 'active' : ''}} href="{{url('/inventory/quantity/' . $sortBy)}}">Quantity</a>
                          <a class="dropdown-item" {{$orderBy == 'desc' ? 'active' : ''}} href="{{url('/inventory/desc/' . $sortBy)}}">Description</a>
                          <a class="dropdown-item" {{$orderBy == 'reg_price' ? 'active' : ''}} href="{{url('/inventory/reg_price/' . $sortBy)}}">Price</a>
                          <a class="dropdown-item" {{$orderBy == 'created_at' ? 'active' : ''}} href="{{url('/inventory/created_at/' . $sortBy)}}">Newest</a>
                        </div>
                      </div>    
                </div>

                <div class="mr-2 float-right my-1" style="font-size: .8em !important; font-family:Verdana, Geneva, Tahoma, sans-serif;">
                    Sort By

                </div>

               

            </div>

                  

        </div>
        
        <hr>

        @include('inc.messages')

        @if (auth()->user()->isAdmin())

            <div class="row">

                <div class="col text-center border border-secondary rounded py-2">

                    <h4 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">CREATE ORDER</h4>
                    <hr>
                    {{Form::open(['url'=> 'createorder'])}}

                        <div class="form-group">

                            <a href="/admin/clients" class="">Client</a>
                
                
                            <?php                     
                                $users = \App\Models\User::where('user_type', 0)->get();                    
                
                                foreach ($users as $user) {
                                    $user->client;
                                }                        
                
                                $user_clients = collect();
                                
                                foreach ($users as $user) {
                
                                    $user_clients->push(collect(['name' => ucfirst($user->first_name) . ' ' . strtoupper(substr($user->middle_name, 0, 1)) . '. ' . ucfirst($user->last_name), 'id' => $user->client->id]));
                
                                }                
                                
                                $list = $user_clients->pluck('name', 'id');
                                $list->all();
                
                            ?>        
                                
                                
                            {{Form::select('client_id', $list , null, ['id' => 'clientSelect', 'title' => 'Select Client', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border border-info rounded-0', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}
                                
                        </div>  

                        <div class="form-group">

                            <a href="/admin/inventory" class="">Item</a>
                
                
                            <?php                     
                                $items = \App\Models\Item::where('quantity', '>', 0)->pluck('desc', 'id');                                                                                                                                      
                
                            ?>        
                                
                                
                            {{Form::select('item_id', $items , null, ['id' => 'itemSelect', 'title' => 'Select Item', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border border-info rounded-0', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}
                                
                        </div>  

                        <div class="d-none" id="adminQuantityInputPanel">

                            <div class="form-group">
                                <label for="">Quantity</label>
                                {{Form::number('quantity', 1, ['id'=> 'adminQuantityInput','class' => 'form-control text-center' , 'placeholder' => 'Quantity' , 'min' => '1', 'max' => ''])}}                            
                            </div>

                            <button type="button" data-toggle="modal" data-target="#createOrder" class="btn btn-success btn-block text-dark rounded-0"><b>Create Order</b></button>

                            <div class="modal fade" id="createOrder" tabindex="-1" role="dialog" aria-labelledby="createOrderTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header bg-success text-dark">
                                      <h5 class="modal-title" id="exampleModalLongTitle">Create Order</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <ul class="list-group">                                        
                                          <li class="list-group-item"><span class="text-muted">Client:</span> <span id="clientName"></span></li>
                                          <li class="list-group-item"><span class="text-muted">Item:</span> <span id="itemName"></span></li>
                                          <li class="list-group-item"><span class="text-muted">Quantity:</span> <span id="quantityName">1</span></li>
                                          <li class="list-group-item"><span class="text-muted">Amount: &#8369;</span> <span id="amountName"></span></li>
                                      </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Create Order</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            
                        </div>


                    {{Form::close()}}
                </div>

            </div>
            
        @endif

        @if (!is_null($the_items->first()))
        
            <div class="row">

                <div class="d-flex justify-content-center container mt-5">                    

         
                    
                    @foreach ($the_items as $item)

                        <div class="card p-3 bg-white mx-auto border border-secondary">
                            <div>
                                <span class="text-muted" style="font-size: .8em !important">{{is_null($item->pet_type_id) ? 'For all type of pets' : 'For ' . $item->type->type . 's'}}</span>
                                <i class="fa fa-circle float-right {{$item->quantity > 0 ? 'text-success' : 'text-secondary'}} "></i> 


                            </div>
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

                            @if (!auth()->user()->isAdmin())
                            {{Form::hidden('item_id', $item->id, ['id' => 'item-'. $item->id])}}
                            {{Form::hidden('client_id', auth()->user()->client->id, ['id' => 'client-' . auth()->user()->client->id])}}
                            {{Form::hidden('price', $item->reg_price, ['id' => 'price-' . $item->id])}}


                                @if(is_null(auth()->user()->client->image) || is_null(auth()->user()->client->dob) || is_null(auth()->user()->client->contact) || is_null(auth()->user()->client->address) || is_null(auth()->user()->client->sex))

                                    <span class="text-danger" style="font-size: .5em !important;">All Personal Information must be set in order to make orders</span>

                                @else

                                    <div class="input-group mb-3">

                                        <div class="input-group-prepend">
                                        <button onclick="decreaseQuantity(document.getElementById('client-{{auth()->user()->client->id}}'),
                                                                            document.getElementById('item-{{$item->id}}'),
                                                                            document.getElementById('quantity-{{$item->id}}'),                                                                                    
                                                                            document.getElementById('price-{{$item->id}}'),
                                                                            document.getElementById('priceLabel-{{$item->id}}')                                                                    
                                                                            )" class="btn btn-outline-info" type="button" 

                                                                            @if ($item->quantity < 1 || \App\Models\Setting::first()->stop_orders == 1)
                                                                                {{'disabled'}}
                                                                            @endif
                                                                            
                                                                            >-</button>
                                        </div>
                                        <input id="quantity-{{$item->id}}" oninput="render(document.getElementById('client-{{auth()->user()->client->id}}'),
                                                                                        document.getElementById('item-{{$item->id}}'),
                                                                                        document.getElementById('quantity-{{$item->id}}'),                                                                                    
                                                                                        document.getElementById('price-{{$item->id}}'),
                                                                                        document.getElementById('priceLabel-{{$item->id}}')                                                                                    
                                                                                        )" type="number" min="1" max="{{$item->quantity}}" class="form-control w-25 text-center" value="0" 

                                                                                        @if ($item->quantity < 1 || \App\Models\Setting::first()->stop_orders == 1)
                                                                                            {{'disabled'}}
                                                                                        @endif
                                                                                        
                                                                                        >
                                        <div class="input-group-append">
                                            <button onclick="increaseQuantity({{$item->quantity}}, 
                                                                            document.getElementById('client-{{auth()->user()->client->id}}'),
                                                                            document.getElementById('item-{{$item->id}}'),
                                                                            document.getElementById('quantity-{{$item->id}}'),                                                                                    
                                                                            document.getElementById('price-{{$item->id}}'),
                                                                            document.getElementById('priceLabel-{{$item->id}}') 
                                                                            )" class="btn btn-outline-info" type="button" 

                                                                            @if ($item->quantity < 1 || \App\Models\Setting::first()->stop_orders == 1)
                                                                              {{'disabled'}}
                                                                            @endif
                                                                            >+</button>
                                        </div>

                                    </div>
                    
                                @endif
                               

                            @endif                          

                            <div class="text-center">                                
                                @if ($item->quantity > 0)
                                    <span class="text-muted" style="font-size: .8em !important;">{{$item->quantity}} items left</span>
                                @else
                                    <span class="text-muted" style="font-size: .8em !important;">Out of stock</span>
                                @endif
                            </div>

                            @if (!auth()->user()->isAdmin())
                                @if(is_null(auth()->user()->client->image) || is_null(auth()->user()->client->dob) || is_null(auth()->user()->client->contact) || is_null(auth()->user()->client->address) || is_null(auth()->user()->client->sex))
                                @else
                                    <button type="button" data-toggle="modal" data-target="#paypal" class="btn btn-block btn-primary rounded-0" {{$item->quantity < 1 ? 'disabled' : ''}}>Buy now</button>                                                                    
                                @endif                           
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
                                    @if (\App\Models\Setting::first()->stop_orders == 1)
                                        <div class="text-center">
                                            Sorry, NVP CLINIC is not accepting orders right now. Check again later.

                                        </div>
                                    @endif
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

let adminQuantityInputPanel = document.getElementById('adminQuantityInputPanel');
let adminQuantityInput = document.getElementById('adminQuantityInput');
let itemSelect = document.getElementById('itemSelect');
let clientSelect = document.getElementById('clientSelect');
let clientName = document.getElementById('clientName');
let itemName = document.getElementById('itemName');
let quantityName = document.getElementById('quantityName');
let amountName = document.getElementById('amountName');
let price = 0;
let quantity = 1;

function updateAmount(){    
    amountName.textContent = price * quantity;
}

adminQuantityInput.addEventListener('change', () => {

    quantityName.textContent = adminQuantityInput.value;
    quantity = adminQuantityInput.value;
    updateAmount();

});

clientSelect.addEventListener('change', () => {

    let xhr = new XMLHttpRequest();        

    xhr.open('GET', APP_URL + '/getclientname/' + clientSelect.value, true);

    xhr.onload = function() {
        if (this.status == 200) {                 
            
            let name = this.responseText;              
            
            clientName.textContent = name;     
        }

    }

    xhr.send(); 
    
});

itemSelect.addEventListener('change', () => {

    let xhr = new XMLHttpRequest();        

    xhr.open('GET', APP_URL + '/getitemquantity/' + itemSelect.value , true);

    xhr.onload = function() {
        if (this.status == 200) {                 
            
            let item = JSON.parse(this.responseText);              
            
            adminQuantityInput.max = item.quantity;
            adminQuantityInputPanel.classList.remove('d-none');
            itemName.textContent = item.desc;  
            price = item.reg_price;
            updateAmount();
        }              

    }

    xhr.send(); 
    
});

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