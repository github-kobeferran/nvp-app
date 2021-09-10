@extends('layouts.app')

@include('inc.banner')

@section('content')

@include('inc.sidebar')

<div class="container">

    <h1>Inventory</h1>


    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @include('inc.messages')
    

    <hr>
    

    <div class="container ml-2">

        <h2>Add an <u>Item</u></h2>

        <div class="row m-4 border p-2">

            <div class="col">

                {!!Form::open(['url' => '/additem'])!!}
            
               <div class="form-group">

                    <label class="float-left mr-2" for="desc">Item Description</label>
                    {{Form::text('desc', '', ['placeholder' => 'Item Description here', 'class' => 'form-control ml-2 w-50 ', 'required' => 'required'])}}
                  

               </div>

               <div class="form-inline mb-4">

                    <label for="type">Item Category</label>

                    <?php                     
                        $categories = \App\Models\ItemCategory::orderBy('desc', 'asc')->pluck('desc', 'id');                    
                    ?>
                    {{Form::select('category_id', $categories , null, ['data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])}}

                </div>   

                <div class="form-inline mb-4">

                    <label for="pet_type">Pet Type</label>
                    <?php                     
                        $types = \App\Models\PetType::orderBy('type', 'asc')->pluck('type', 'id');                    
                    ?>
                    {{Form::select('type_id', $types , null, [ 'id' => 'typeSelect', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;',  'title' => 'Choose a Pet Type'])}}
                    {{Form::hidden('all_types', 0)}}
                    <input name="all_types" value="1" type="checkbox" style="width: 25px; height:25px;" class="form-check-input mx-2 bg-secondary" id="alltypescheck">                    
                    <label class="form-check-label" style="font-size: 1rem;">For All Pet Types</label>

                </div>

                <div class="form-inline mb-4">

                    <label for="quantity">Quantity</label>
                    {{Form::number('quantity', 1, ['class' => 'form-control ml-2', 'min' => '1', 'max' => '10000'])}}

                </div>

                <div class="form-inline mb-4">

                    <label for="quantity">Dealers Price</label>
                    <span class="ml-3">&#8369;</span>{{Form::number('deal_price', '5.00', ['class' => 'form-control ml-1', 'min' => '5', 'max' => '100000', 'step' => '0.01'])}}

                </div>

                <div class="form-inline mb-4">

                    <label for="quantity">Regular Price</label>
                    <span class="ml-3">&#8369;</span>{{Form::number('reg_price', '5.00', ['class' => 'form-control ml-1', 'min' => '5', 'max' => '100000', 'step' => '0.01'])}}

                </div>

                <label for="">Note</label>                    
                <div class="form-inline mb-4">
                    {{Form::textarea('note', '', ['class' => 'form-control ml-2', 'placeholder' => 'say note about the item here.. (optional)'])}}

                </div>

                <button type="submit" class="btn btn-lg btn-primary float-right mr-3">Submit</button>

            {!!Form::close()!!}

            </div>

        </div>

    </div>

    <hr>
    <hr class="bg-primary">

    <div class="container ml-4 ">        

        <div class="row ">

            <div class="col-8">

                @empty(\App\Models\ItemCategory::all())

                @else

                <div class="table-responsive">

                    
                    <table id="categoryTable" class="table table-striped table-bordered" style="max-height: 200px ">

                        <thead>
                            <tr>
                                <th>Description</th>
                                <th style="width: 20px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\ItemCategory::all() as $category)
                                <tr>
                                    <td>{{strtoupper($category->desc)}}</td>
                                    <td colspan="2" >

                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCat-{{$category->id}}">
                                            Edit
                                            </button> 
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCat-{{$category->id}}">
                                            Delete
                                            </button> 

                                    </td>
                                </tr>

                                <div class="modal fade"  id="editCat-{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Edit <b>{{ucfirst($category->desc)}}</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        {!!Form::open(['url' => '/updatecat'])!!}
                                        <div class="modal-body">

                                            {{Form::hidden('id', $category->id)}}
                                            <div class="form-group">
                                                <label for="">Category Description</label>
                                                {{Form::text('desc', $category->desc, ['class' => 'form-control', 'required' => 'required'])}}
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>                                                
                                            <button type="submit" class="btn btn-primary">Save</button>        
                                        </div>
                                        {!!Form::close()!!}
                                    </div>
                                    </div>
                                </div>

                                <div class="modal fade"  id="deleteCat-{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Delete <b>{{ucfirst($category->desc)}}</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                        You sure you want to delete category: <b>{{ucfirst($category->desc)}}</b>?
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        {!!Form::open(['url' => '/deletecat'])!!}
            
                                            {{Form::hidden('id', $category->id)}}
            
                                            <button type="submit" class="btn btn-primary">Yes</button>
            
                                        {!!Form::close()!!}
                                        </div>
                                    </div>
                                    </div>
                                </div>

                            @endforeach
                        </tbody>
                    
                    </table>

                </div>
                    
                @endempty
                
            </div>

            
            <div class="col-4 text-right">
                
                <br>
                <h3 class="">Add an <u>Item Category</u></h3>
                <br>
            {!!Form::open(['url' => '/addcat'])!!}
            
               <div class="form-inline mr-0 mb-4">

                    <label for="desc">Category Description</label>
                    {{Form::text('desc', '', ['class' => 'form-control ml-2'])}}                  

               </div>

               <button type="submit" class="btn btn-info float-right mr-2">Add Category</button>

               
            {!!Form::close()!!}

            </div>

        </div>

    </div>

    <hr class="bg-primary">

    @empty(\App\Models\Item::first())

    @else
        

        <div>
            <h1 class="text-center">Items Table</h1>
            <span class="float-right"><a href="/itemexport" class="btn btn-success text-dark shadow"><b>Export Items to Excel</b></a></span>
        </div>

        <table id="itemsTable" class="table table-striped table-bordered" style="">

            <thead class="bg-info">

                <tr>

                    <th>Date Added</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Pet Type</th>
                    <th>Dealers Price</th>
                    <th>Regular Price</th>
                    <th>Quantity</th>
                    <th style="width: 30px;">Action</th>

                </tr>
                
            </thead>

            <tbody>

                @foreach (\App\Models\Item::all() as $item)

                    <tr>

                        <td>{{\Carbon\Carbon::parse($item->created_at)->isoFormat('D, MMM OY-h:mm a')}}</td>
                        <td>{{$item->desc}}</td>
                        <td>{{$item->category->desc}}</td>

                        @empty($item->pet_type_id)
                            
                            <td>For All Pet Types</td>      

                        @else

                            <td>{{ucfirst($item->type->type)}}</td>  

                        @endisset

                        <td>&#8369; {{$item->deal_price}}</td>
                        <td>&#8369; {{$item->reg_price}}</td>
                        <td>{{$item->quantity}}</td>

                        <td colspan="3" >

                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editItem-{{$item->id}}">
                                Edit
                             </button> 
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteItem-{{$item->id}}">
                                Delete
                             </button> 
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#out-{{$item->id}}">
                                Out of Stock
                             </button> 

                        </td>

                    </tr>

                    <div class="modal fade" id="editItem-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">                    
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content"  style="max-width: 80% !important;" >
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Edit <b>{{ucfirst($item->desc)}}</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            {!!Form::open(['url' => 'updateitem'])!!}
                            <div class="modal-body">

                                {{Form::hidden('id', $item->id)}}
                                <div class="form-group">
                                    
                                    <label class="float-left mr-2" for="desc">Item Description</label>
                                    {{Form::text('desc', $item->desc, ['class' => 'form-control ml-2  ', 'required' => 'required'])}}

                                </div>
                                <div class="form-group">
                                    
                                    <label class="" for="desc">Item Category</label>
                                    <?php                     
                                        $categories = \App\Models\ItemCategory::orderBy('desc', 'asc')->pluck('desc', 'id');                    
                                    ?>
                                    {{Form::select('asdf', $categories , $item->item_category_id, ['id' => 'cat_select', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;'])}}
                                    {{Form::hidden('category_id', $item->item_category_id, ['id' => 'hidden-cat'])}}

                                    <script>
                                        let cat_select = document.getElementById('cat_select');
                                        let hidden_cat_id = document.getElementById('hidden-cat');

                                        cat_select.addEventListener('change', () => {
                                            hidden_cat_id.value = cat_select.value;
                                            console.log(cat_select.value);
                                        });

                                    </script>

                                </div>

                                 <div class="form-group">

                                    <label for="pet_type">Pet Type</label>
                                    <?php                     
                                        $types = \App\Models\PetType::orderBy('type', 'asc')->pluck('type', 'id');                    
                                    ?>
                                    {{Form::select('qwe', $types , $item->pet_type_id, [$item->pet_type_id ? '' : 'disabled' => 'disabled', 'id' => 'typeSelect-edit', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;',  ' title' => 'Choose a Pet Type'])}}
                                    {{Form::hidden('all_types', 0)}}
                                    <input name="all_types" value="1" type="checkbox" style="width: 25px; height:25px;" class="form-check-input mx-2 bg-secondary" id="alltypescheck-edit" {{$item->pet_type_id ? '' : 'checked'}}>                    
                                    <label class="form-check-label" style="font-size: 1rem;">For All Pet Types</label>
                                    {{Form::hidden('type_id', $item->pet_type_id, ['id' => 'hidden-type'])}}

                                    <script>
                                        let type_select = document.getElementById('typeSelect-edit');
                                        let hidden_type_id = document.getElementById('hidden-type');

                                        type_select.addEventListener('change', () => {
                                            hidden_type_id.value = type_select.value;
                                            
                                        });

                                    </script>

                                </div>

                                <div class="form-group">

                                    <label for="quantity">Quantity</label>
                                    {{Form::number('quantity', $item->quantity, ['class' => 'form-control ml-2', 'min' => '1', 'max' => '10000'])}}

                                </div>

                                <div class="form-group">

                                    <label for="quantity">Dealers Price</label>
                                    <span class="ml-3">&#8369;</span>{{Form::number('deal_price', $item->deal_price, ['class' => 'form-control ml-1', 'min' => '5', 'max' => '100000', 'step' => '0.01'])}}

                                </div>

                                <div class="form-group">

                                    <label for="quantity">Regular Price</label>
                                    <span class="ml-3">&#8369;</span>{{Form::number('reg_price', $item->reg_price, ['class' => 'form-control ml-1', 'min' => '5', 'max' => '100000', 'step' => '0.01'])}}

                                </div>

                                <label for="">Note</label>                    
                                <div class="form-inline mb-4">
                                    {{Form::textarea('note', $item->note, ['class' => 'form-control ml-2', 'placeholder' => 'say note about the item here.. (optional)'])}}

                                </div>
                            
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>                            
                            </div>
                            {!!Form::close()!!}
                        </div>
                        </div>
                    </div>

                    <div class="modal fade"  id="deleteItem-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Delete <b>{{ucfirst($item->desc)}}</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            You sure you want to delete {{ucfirst($item->desc)}}?
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            {!!Form::open(['url' => 'deleteitem'])!!}

                                {{Form::hidden('id', $item->id)}}

                                <button type="submit" class="btn btn-primary">Yes</button>

                            {!!Form::close()!!}
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="modal fade"  id="out-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">No stocks of <b>{{ucfirst($item->desc)}}</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            Set {{ucfirst($item->desc)}} to <b>Out of Stock</b>?
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            {!!Form::open(['url' => 'outofstock'])!!}

                                {{Form::hidden('id', $item->id)}}

                                <button type="submit" class="btn btn-primary">Yes</button>

                            {!!Form::close()!!}
                            </div>
                        </div>
                        </div>
                    </div>

                    
                @endforeach
            </tbody>

        </table>
        
    @endempty
         
</div>


<script>

window.onload = function() {
    toggleTypeSelect();
};

let typeSelect = document.getElementById('typeSelect');
let alltypescheck = document.getElementById('alltypescheck');
let alltypescheckEdit = document.getElementById('alltypescheck-edit');
let typeSelectEdit = document.getElementById('typeSelect-edit');

alltypescheck.addEventListener('change', toggleTypeSelect);
alltypescheckEdit.addEventListener('change', toggleTypeSelectEdit);


$(document).ready( function () {
    $('#itemsTable').DataTable(
        {
        "order": [[ 0, "desc" ]]
    });
    $('#categoryTable').DataTable();
} );


function toggleTypeSelect(){    

    if(alltypescheck.checked == true)
        typeSelect.disabled  = true;
    else {
        typeSelect.disabled  = false;
        typeSelect.required = true;
    }
        
    $('.selectpicker').selectpicker('refresh');        

}

function toggleTypeSelectEdit(){    

    if(alltypescheckEdit.checked == true){
        typeSelectEdit.disabled  = true;        
    }
    else {
        typeSelectEdit.disabled  = false;
        typeSelectEdit.required = true;
    }
        
    $('.selectpicker').selectpicker('refresh');
        

}

</script>

@endsection