

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

<div class="container">

    <div class="text-center">

        <?php if(empty($pet->image)): ?>

            <img class="pet-profile-pic" src="<?php echo e(url('storage/images/pet/no-image-pet.jpg')); ?>" alt="">

        <?php else: ?>

            <img class="pet-profile-pic" src="<?php echo e(url('storage/images/pet/' . $pet->image)); ?>" alt="">
           
        <?php endif; ?>     

        <h2 class="m-2 text-secondary "><i class="fa fa-paw" style="font-style: italic;" aria-hidden="true"></i> <?php echo e(ucfirst($pet->name)); ?> <i class="fa fa-paw" style="font-style: italic;" aria-hidden="true"></i></h2>
        <em> <h5 class="m-2 text-secondary ">Birthday: <?php echo e($pet->dob_string); ?> | <?php echo e($pet->age); ?></h5></em>

    </div>   
    
    <div class="table-responsive-lg">

        <table class="table table-bordered table-client text-center p-5">

            <?php if(auth()->user()->isAdmin()): ?>
                        
                <tr>
                    <td>Owner: <a href="<?php echo e(url('/user/' .  $pet->owner->user->email)); ?>"><?php echo e(ucfirst($pet->owner->user->name)); ?></a></td>  
                </tr>
                
            <?php endif; ?>

            <?php if(empty($pet->sex)): ?>
                
            <?php else: ?>
                <tr>
                    <td><?php echo e($pet->sex ? 'Female' : 'Male'); ?></td>  
                </tr>
            <?php endif; ?>        

            <?php if(empty($pet->type)): ?>
                
            <?php else: ?>
                <tr>
                    <td><?php echo e(ucfirst($pet->type->type)); ?> : <em><?php echo e(ucfirst($pet->breed)); ?></em></td>  
                </tr>
            <?php endif; ?>        

            <?php if(isset($pet->weight) && isset($pet->height)): ?>
            
                <tr>
                    <td><?php echo e($pet->weight); ?> kg | <?php echo e($pet->height); ?> cm</td>
                </tr>

            <?php elseif(isset($pet->weight) && !isset($pet->height)): ?>

                <tr>
                    <td><?php echo e($pet->weight); ?> kg </td>
                </tr>

            <?php elseif(!isset($pet->weight) && isset($pet->height)): ?>

                <tr>
                    <td><?php echo e($pet->height); ?> cm </td>
                </tr>

            <?php endif; ?>

            <tr>

                <td> <?php echo e($pet->checked ? 'Have visited the Clinic' : 'Have not visited the Clinic'); ?>  </td>

            </tr>

            <?php if(!auth()->user()->isAdmin()): ?>
                
                <tr>

                    <td><a href="<?php echo e(url('/editpet/' . $pet->name)); ?>"> Edit <?php echo e($pet->name); ?>'s Details</a></td>

                </tr>

            <?php endif; ?>
                                

        </table>

    </div>

    <hr>

    

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/pet/show.blade.php ENDPATH**/ ?>