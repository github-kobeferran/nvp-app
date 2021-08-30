

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('inc.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container mt-2">

    <h2><a class="btn btn-light" href="/user"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Profile</a>  Personal Information  </h2> 
    
    <hr>

    <?php if(session('status')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <?php echo $__env->make('inc.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo Form::open(['url' => '/updateuser', 'files' => true, 'class' => 'border-2 m-2 ']); ?>


        <?php echo e(Form::hidden('id', $client->id)); ?>


        <div class="form-inline">
            <label class="text-muted" for="image">Image</label>            
            <?php echo e(Form::file('image', ['class' => 'form-control border-0  ml-3'])); ?>

        </div>

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="name">Name</label>
            <?php echo e(Form::text('name', $user->name, ['class' => 'form-control form-control-lg ml-2 w-75'])); ?>                        

        </div>

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="sex">Sex</label>
            <?php echo e(Form::select('sex', [null => 'Select from options', '0' => 'Male', '1' => 'Female'], $client->sex, ['class' => 'form-control form-control-lg ml-3'])); ?>


        </div>

        <hr>

        <div class="form-inline my-2"">

            <label class="text-muted" for="dob">Date of Birth</label>
            <?php echo e(Form::date('dob', $client->dob, ['class' => 'form-control form-control-lg ml-3'])); ?>


        </div>

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="contact">Contact</label>
            <?php echo e(Form::text('contact', $client->contact, ['maxlength' => '15', 'class' => 'form-control ml-2 w-75'])); ?>                        

        </div>

        <hr>

        <div class="form-inline my-2">

            <label class="text-muted" for="adress">Address</label>
            <?php echo e(Form::text('address', $client->address, ['maxlength' => '100', 'class' => 'form-control ml-2 w-75'])); ?>                        

        </div>

        <hr>

        <button class="btn btn-block btn-primary float-right mt-4">Submit</button>

    <?php echo Form::close(); ?>


</div>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/client/edit.blade.php ENDPATH**/ ?>