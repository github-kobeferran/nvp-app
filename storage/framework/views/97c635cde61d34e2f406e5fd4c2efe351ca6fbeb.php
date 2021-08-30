

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('inc.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="container">

        <h1>Services</h1>

        <?php if(session('status')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->make('inc.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="container ml-2">

            <hr>

            <h3 ><i class="fa fa-plus text-dark" style="font-size: 1rem;"></i> Add a Service</h3>            

            <hr>

            <?php echo Form::open(['url' => '/storeservice']); ?>


                <div class="form-group">
                    <label for="">Service Description</label>
                    <?php echo e(Form::text('desc', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Description here..'])); ?>

                </div>

                <label for="">Service Fee</label>
                <div class="form-inline">
                    &#8369; <?php echo e(Form::number('price', 50, ['class' => 'form-control ml-2', 'min' => '50', 'max' => '50000'])); ?>

                </div>

                <button type="submit" class="btn btn-lg btn-primary float-right my-2">Submit</button>                
                <br>
                <br>
                <hr>

            <?php echo Form::close(); ?>


        </div>

        <?php if(empty(\App\Models\Service::all())): ?>
            

        <?php else: ?>
            <br>
           <hr class="w-50 bg-dark">

           <h3 class="text-center">Services Table</h3>

            <table id="servicesTable" class="table table-striped table-bordered" style="">

                <thead class="bg-primary text-white">
    
                    <tr>
    
                        <th>Description</th>
                        <th>Fee</th>
                        <th style="width: 20px;">Action</th>
    
                    </tr>
    
                </thead>
    
                <tbody>
    
                    <?php $__currentLoopData = \App\Models\Service::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>
                            <td><?php echo e($service->desc); ?></td>
                            <td>&#8369; <?php echo e($service->price); ?></td>
                            <td colspan="2" >

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editService-<?php echo e($service->id); ?>">
                                    Edit
                                    </button> 
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteService-<?php echo e($service->id); ?>">
                                    Delete
                                </button> 

                            </td>
                        </tr>

                        <div class="modal fade"  id="editService-<?php echo e($service->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit <b><?php echo e(ucfirst($service->desc)); ?></b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <?php echo Form::open(['url' => '/updateservice']); ?>

                                <div class="modal-body">

                                    <?php echo e(Form::hidden('id', $service->id)); ?>

                                    <div class="form-group">
                                        <label for="">Service Description</label>
                                        <?php echo e(Form::text('desc', $service->desc, ['class' => 'form-control', 'required' => 'required'])); ?>

                                    </div>
                                    
                                    <label for="">Fee</label>
                                    <div class="form-inline">

                                        &#8369;<?php echo e(Form::number('price', $service->price, ['class' => 'form-control ml-2', 'min' => '50', 'max' => '50000'])); ?>


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

                        <div class="modal fade"  id="deleteService-<?php echo e($service->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Delete <b><?php echo e(ucfirst($service->desc)); ?></b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                You sure you want to delete service: <b><?php echo e(ucfirst($service->desc)); ?></b>?
                                </div>
                                <?php echo Form::open(['url' => '/deleteservice']); ?>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    
                                    <?php echo e(Form::hidden('id', $service->id)); ?>

    
                                    <button type="submit" class="btn btn-primary">Yes</button>
    
                                <?php echo Form::close(); ?>

                                </div>
                            </div>
                            </div>
                        </div>
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
                </tbody>
    
    
            </table>
    
            
        <?php endif; ?>


        
    </div>

<script>

$(document).ready( function () {
    // $('#itemsTable').DataTable(
    //     {
    //     "order": [[ 0, "desc" ]]
    // });
    $('#servicesTable').DataTable();
} );

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/admin/view/services.blade.php ENDPATH**/ ?>