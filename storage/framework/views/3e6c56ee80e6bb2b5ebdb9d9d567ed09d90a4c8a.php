<?php if(count($errors) > 0): ?>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>     
        <div class="alert alert-danger " role="alert">
            <?php echo e($error); ?>

        
        </div>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if(session('success')): ?>  
    <div class="alert alert-success" role="alert">
        <?php echo e(session('success')); ?>

        
    </div>

<?php endif; ?>

<?php if(session('error')): ?>  
    <div class="alert alert-danger " role="alert">
        <?php echo e(session('error')); ?>

       
    </div>
<?php endif; ?>

<?php if(session('warning')): ?>  
    <div class="alert alert-warning " role="alert">
        <?php echo e(session('warning')); ?>

       
    </div>
<?php endif; ?>

<?php if(session('info')): ?>  
    <div class="alert alert-info " role="alert">
        <?php echo e(session('info')); ?>

        
    </div>
<?php endif; ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/inc/messages.blade.php ENDPATH**/ ?>