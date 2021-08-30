

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

<div class="container ">

    <h2 class="text-center">Edit <?php echo e($pet->name); ?>'s Details</h2>

    <?php if(session('status')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <?php echo $__env->make('inc.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    

    <?php echo Form::open(['url' => '/updatepet', 'files' => true, 'id' => 'petForm', 'class' => 'p-4']); ?>


        <?php echo e(Form::hidden('id', $pet->id)); ?>


        <div class="form-check mb-4">
            <?php if(empty($pet->image)): ?>
            
            <img src="<?php echo e(url('storage/images/pet/no-image-pet.jpg')); ?>" alt="" style="width: 200px; height: 200px;">
            
            <?php else: ?>
            
            <img src="<?php echo e(url('storage/images/pet/' . $pet->image)); ?>" alt="" style="width: 200px; height: 200px;">
            
            <?php endif; ?>
            <label class="" for="image">Change Image</label>            
            <?php echo e(Form::file('image', ['class' => ' border-0 ml-2 mt-2'])); ?>

        </div>

        <div class="form-inline mb-4">
            <label for="name">Pet Name</label>
            <?php echo e(Form::text('name', $pet->name, ['class' => 'form-control ml-2', 'required' => 'required'])); ?>

        </div>        

        <div class="form-inline mb-4">
            <label for="type">Pet Type</label>

            <?php                     
                $pet_types = \App\Models\PetType::orderBy('type', 'asc')->pluck('type', 'id');                    
            ?>

            <?php echo e(Form::select('type_id', $pet_types , $pet->type->id, ['data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;'])); ?>

        </div>          

        <div class="form-inline mb-4">
            <label for="breed">Breed</label>                
            <?php echo e(Form::text('breed', $pet->breed, ['class' => 'form-control ml-2', 'required' => 'required'])); ?>

        </div>

        <div class="form-inline mb-4">
            <label for="color">Color</label>                
            <?php echo e(Form::text('color', $pet->color, ['maxlength' => '50', 'class' => 'form-control ml-2', 'required' => 'required'])); ?>

        </div>

        <div class="form-inline mb-4">
            <label for="dob">Date of Birth</label>                
            <?php echo e(Form::date('dob', $pet->dob, ['class' => 'form-control ml-2', 'required' => 'required'])); ?>

        </div>

        <div class="form-inline mb-4">    

            <?php if($pet->sex == 0): ?>

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
            
                          
        </div>

        <div class="form-inline mb-4">
            <label for="weight">Weight (kg)</label>
            <?php echo e(Form::number('height', $pet->weight, ['class' => 'form-control form-control-lg mx-2', 'step' => '.01', 'min' => '.01', 'required' => 'required'])); ?>

            
        </div>

        <div class="form-inline mb-4">
            <label for="weight">Height (cm)</label>
            <?php echo e(Form::number('weight', $pet->height, ['class' => 'form-control form-control-lg mx-2', 'min' => '1', 'required' => 'required'])); ?>

            
        </div>

        <?php if(auth()->user()->isAdmin()): ?>

            <div class="form-inline mb-4">
                <label for="weight">Have been in Clinic?</label>
                <?php echo e(Form::checkbox('checked', 1, true, ['class' => ' ml-2', 'style' => 'width: 25px; height: 25px;'])); ?>

                <?php echo e(Form::hidden('checked', 0)); ?>

            </div>
            
        <?php endif; ?>
        
        



        <button type="submit" class="btn btn-primary btn-lg float-right">Submit</button>            
        <br>

    <?php echo Form::close(); ?>


</div>

<script>


</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/pet/edit.blade.php ENDPATH**/ ?>