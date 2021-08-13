@extends('layouts.app')

@include('inc.banner')

@section('content')

    @empty(\App\Models\Item::all())
        
    @else

        <div class="container text-center">

            <h1>Our Products</h1> 

            <hr>
            
            <table id="products" class="table table-bordered">
                <thead class="bg-info">
                    <tr>
                        <th>Description</th>
                        <th>Category</th>
                        <th>For</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\App\Models\Item::orderBy('desc', 'asc')->get() as $item)
                    <tr>
                        <td>{{ucfirst($item->desc)}}</td>
                        <td>{{strtoupper($item->category->desc)}}</td>
                        <td>{{is_null($item->pet_type_id) ? 'For All Pet Types' : ucfirst($item->type->type)}}</td>
                        <td>&#8369; {{$item->reg_price}}</td>
                        <td>{{$item->pet_type_id ? 'Available' : 'Out of Stock'}}</td>
                    </tr>
                    @endforeach
                    
                </tbody>                
            </table>

        </div>

    @endempty


<script>

$(document).ready( function () {
    $('#products').DataTable();
} );

</script>
@endsection