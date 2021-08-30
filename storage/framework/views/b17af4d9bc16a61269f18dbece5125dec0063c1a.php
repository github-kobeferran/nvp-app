

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('inc.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container">

    <h1>Employees</h1>

    <?php if(session('status')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <?php echo $__env->make('inc.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo Form::open(['url' => 'employeestore', 'class' => 'border ml-2 p-4']); ?>          

        <h3>Add an Employee</h3>

        <hr>

        <div class="form-inline ">
            <label for="name">Name</label>
            <?php echo e(Form::text('name', '', ['placeholder' => 'Name..', 'class' => 'form-control ml-2 w-50', 'required' => 'required'])); ?>

        </div>

        <hr>

        <div class="form-inline ">
            <label for="dob">Date of Birth</label>                
            <?php echo e(Form::date('dob', null, ['class' => 'form-control ml-2', 'required' => 'required'])); ?>

        </div>

        <hr>

        <div class="form-inline mb-4">                
            <?php echo e(Form::radio('sex', '0', true, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])); ?>

            <label for="sex" class="mx-2">Male</label>                
            <?php echo e(Form::radio('sex', '1', false, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])); ?>

            <label for="sex" class="mx-2">Female</label>                
        </div>

        <hr>

        <div class="form-inline ">

            <label class="text-muted" for="contact">Contact</label>
            <?php echo e(Form::text('contact', '', ['maxlength' => '15', 'class' => 'form-control ml-2 w-75'])); ?>                        

        </div>

        <hr>

        <div class="form-inline ">

            <label class="text-muted" for="adress">Address</label>
            <?php echo e(Form::text('address', '', ['maxlength' => '100', 'class' => 'form-control ml-2 w-75'])); ?>                        

        </div>

        <hr>

        <button type="submit" class="btn-lg btn-primary float-right">Add Employee</button>

        <br>

    <?php echo Form::close(); ?>


    <hr class="text-dark">

    <div class="input-group mt-3 mb-1">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>
          </span>
        </div>
        <input id="searchBox" type="text" class="form-control form-control-lg" placeholder="Search.." aria-label="Username" aria-nameribedby="basic-addon1">
    </div>

    <div  class="table-responsive" style="max-height: 800px; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;">

        <table id="employees" class="table table-bordered ">

            <thead class="bg-warning text-dark">
                <tr>
                    <th>Name</th>                
                    <th>Sex</th>                
                    <th>Date of Birth</th>                
                    <th>Address</th>                
                    <th>Contact</th>                
                    <th style="width: 20px;">Action</th>                
                </tr>
            </thead>
                
            <?php if(empty(\App\Models\Employee::first())): ?>

            <?php else: ?>

            <tbody id="employee-list" > 

                <?php $__currentLoopData = \App\Models\Employee::orderBy('name', 'asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                 <tr>
                    
                    <td><?php echo e(ucfirst($employee->name)); ?></td>
                    <td><?php echo e($employee->sex == 0 ? 'Male' : 'Female'); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($employee->dob)->isoFormat('MMM DD, OY') . ' (' . \Carbon\Carbon::parse($employee->dob)->age . ' yrs)'); ?></td>
                    <td><?php echo e($employee->address); ?></td>
                    <td><?php echo e($employee->contact); ?></td>
                    <td colspan="2">

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editEmployee-<?php echo e($employee->id); ?>">
                            Edit
                            </button> 
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteEmployee-<?php echo e($employee->id); ?>">
                            Delete
                        </button>

                    </td>
                 </tr>

                 
                 <div class="modal fade"  id="editEmployee-<?php echo e($employee->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit <b><?php echo e(ucfirst($employee->name)); ?></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <?php echo Form::open(['url' => '/employeeupdate']); ?>

                        <div class="modal-body">

                            <?php echo e(Form::hidden('id', $employee->id)); ?>

                            <div class="form-inline ">
                                <label for="name">Name</label>
                                <?php echo e(Form::text('name', $employee->name, ['placeholder' => 'Name..', 'class' => 'form-control ml-2 w-50', 'required' => 'required'])); ?>

                            </div>
                    
                            <hr>
                    
                            <div class="form-inline ">
                                <label for="dob">Date of Birth</label>                
                                <?php echo e(Form::date('dob', $employee->dob, ['class' => 'form-control ml-2', 'required' => 'required'])); ?>

                            </div>
                    
                            <hr>
                    
                            <?php if($employee->sex == 0): ?>

                            <?php echo e(Form::radio('sex', '0', true, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])); ?>

                            <label for="sex" class="mx-2">Male</label>                
                            <?php echo e(Form::radio('sex', '1', false, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])); ?>

                            <label for="sex" class="mx-2">Female</label>  
                            
                        <?php else: ?>
            
                            <?php echo e(Form::radio('sex', '0', false, ['class' => 'form-control-input ml-2', 'style' => 'width: 18px; height: 18px;'])); ?>

                            <label for="sex" class="mx-2">Male</label>                
                            <?php echo e(Form::radio('sex', '1', true, ['class' => 'form-control-input', 'style' => 'width: 18px; height: 18px;'])); ?>

                            <label for="sex" class="mx-2">Female</label>  
                            
                        <?php endif; ?>
                        
                    
                            <hr>
                    
                            <div class="form-inline ">
                    
                                <label class="text-muted" for="contact">Contact</label>
                                <?php echo e(Form::text('contact', $employee->contact, ['maxlength' => '15', 'class' => 'form-control ml-2 w-75'])); ?>                        
                    
                            </div>
                    
                            <hr>
                    
                            <div class="form-inline ">
                    
                                <label class="text-muted" for="adress">Address</label>
                                <?php echo e(Form::text('address', $employee->address, ['maxlength' => '100', 'class' => 'form-control ml-2 w-75'])); ?>                        
                    
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>                                                
                            <button type="submit" class="btn btn-primary">Save</button>        
                        </div>
                        <?php echo Form::close(); ?>

                    </div>
                    </div>
                </div>

                <div class="modal fade"  id="deleteEmployee-<?php echo e($employee->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Delete <b><?php echo e(ucfirst($employee->name)); ?></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        You sure you want to delete employee: <b><?php echo e(ucfirst($employee->name)); ?></b>?
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <?php echo Form::open(['url' => '/employeedelete']); ?>


                            <?php echo e(Form::hidden('id', $employee->id)); ?>


                            <button type="submit" class="btn btn-primary">Yes</button>

                        <?php echo Form::close(); ?>

                        </div>
                    </div>
                    </div>
                </div>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
                
            <?php endif; ?>

        </table>

    </div>

</div>

<script>

$(document).ready( function () {  
    $('#employees').DataTable();
} );

let searchBox = document.getElementById('searchBox');
let clientList = document.getElementById('client-list');

searchBox.addEventListener('keyup', searchClient);

function searchClient(){

    let txt = searchBox.value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/clients/search/'+ txt, true);

    xhr.onload = function() {
        if (this.status == 200) {

            let users = JSON.parse(this.responseText);

            let output = `<tbody id="client-list">`;

            for(let i in users){

                output+= `<tr>`;    

                  
                output+= `</tr>`;    

            }

            output+= `</tbody>`;

            clientList.innerHTML = output;
        
        } 
    }    

    xhr.send();

}

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/admin/view/employees.blade.php ENDPATH**/ ?>