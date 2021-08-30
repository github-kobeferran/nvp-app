

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('inc.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <div class="container">

        <div class="text-center">

            <?php if(empty($user->client->image)): ?>

                <img class="profile-pic" src="<?php echo e(url('storage/images/client/no-image.jpg')); ?>" alt="">

            <?php else: ?>

                <img class="profile-pic" src="<?php echo e(url('storage/images/client/' . $user->client->image)); ?>" alt="">
               
            <?php endif; ?>     

            <h2 class="m-2 text-secondary "> <?php echo e($user->name); ?> </h2>
            <em> <h5 class="m-2 text-secondary "><?php echo e($user->email); ?></h5></em>

        </div>     

       
        <div class="table-responsive-lg">

            <table class="table table-bordered table-client text-center p-5">

                <tr>

                    <th>Sex</th>                    
                    
                    <?php if(isset($user->client->sex)): ?>

                    <td><?php echo e($user->client->sex ? 'Female' : 'Male'); ?></td>  

                    
                    <?php else: ?>
                    
                    
                    <td>N/A</td>

                    <?php endif; ?>
                    
                </tr>
                
                <tr>

                    <th>Age</th>                    
                    
                    <?php if(empty($user->client->dob)): ?>

                        <td>N/A</td>
                    
                    <?php else: ?>

                        <td><?php echo e(\Carbon\Carbon::parse($user->client->dob)->age); ?> years </td>                    

                    <?php endif; ?>
                    
                </tr>

                <tr>

                    <th>Contact</th>                    
                    
                    <?php if(empty($user->client->contact)): ?>

                        <td>N/A</td>
                    
                    <?php else: ?>

                        <td><?php echo e($user->client->contact); ?></td>                    

                    <?php endif; ?>
                    
                </tr>
                <tr>

                    <th>Address</th>                    
                    <?php if(empty($user->client->address)): ?>

                        <td>N/A</td>
                
                    <?php else: ?>

                        <td><?php echo e($user->client->address); ?></td>                    

                    <?php endif; ?>

                </tr>            

            </table>            

        </div>

        <?php if(!auth()->user()->isAdmin()): ?>

            <div class="btn-group dropleft float-right">
                <button type="button" class="btn-lg btn-info text-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
                </button>
                <div class="dropdown-menu ">

                    <?php if(is_null($user->client->image) || is_null($user->client->dob) || is_null($user->client->contact) || is_null($user->client->address) || is_null($user->client->sex)): ?>

                        <!-- Button trigger modal -->                    
                        <button type="button" class="dropdown-item border-bottom" data-toggle="modal" data-target="#updateInfoFirst">
                            Register a Pet
                        </button>                                                                              

                    <?php else: ?>

                        <a href="<?php echo e(url('/createpet/' . $user->email)); ?>" class="dropdown-item border-bottom">Register a Pet</a>
                        
                    <?php endif; ?>
                    
                    <a href="/edituser" class="dropdown-item">Change Personal Information</a>
                
                </div>
            </div>
            
        <?php endif; ?>

        <div class="modal fade" id="updateInfoFirst" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    You must update your personal information first.
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                
                </div>
            </div>
            </div>
        </div>

        <br>
        <br>

        <?php if(auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(url('createpet/' . $user->email)); ?>" class="btn-lg btn-primary float-right"><i class="fa fa-paw text-danger" aria-hidden="true"></i>  Add Pet to this Client</a>
            <br>
            <br>
        <?php endif; ?>   
                

        <?php if(empty($user->client->pet)): ?>
            
        <?php else: ?>

            
            <div class="toony-text-lg text-center text-danger">

                <?php if(auth()->user()->isAdmin()): ?>
                    <?php echo e($user->name); ?>'s Pets
                <?php else: ?>
                    My Pets
                <?php endif; ?>

            </div>
            

            <div class="d-flex flex-wrap">

                <?php $__currentLoopData = $user->client->pet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php if(empty($pet->image)): ?>

                        <img data-toggle="tooltip" title="<?php echo e(ucfirst($pet->name)); ?>" onclick="redirectToPet('<?php echo e($user->email); ?>', '<?php echo e($pet->name); ?>')" class="pets-pic" src="<?php echo e(url('storage/images/pet/no-image-pet.jpg')); ?>" alt="">

                    <?php else: ?>
                    
                    <img data-toggle="tooltip" title="<?php echo e(ucfirst($pet->name)); ?>" onclick="redirectToPet('<?php echo e($user->email); ?>', '<?php echo e($pet->name); ?>')" class="pets-pic" src="<?php echo e(url('storage/images/pet/' . $pet->image)); ?>" alt="">

                    <?php endif; ?>

                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                

            </div>

        <?php endif; ?>

    </div>


<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

function redirectToPet(clientEmail, petName){

    window.location.href = APP_URL + '/pet/' + clientEmail + '/' + petName;

}

</script>

    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/client/dashboard.blade.php ENDPATH**/ ?>