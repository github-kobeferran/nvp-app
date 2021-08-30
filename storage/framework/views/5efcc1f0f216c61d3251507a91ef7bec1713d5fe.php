<?php $__env->startComponent('mail::message'); ?>
Hello, <?php echo e(ucfirst($name)); ?> !

<?php $__env->startComponent('mail::button', ['url' => url('/login')]); ?>
Login
<?php if (isset($__componentOriginalb8f5c8a6ad1b73985c32a4b97acff83989288b9e)): ?>
<?php $component = $__componentOriginalb8f5c8a6ad1b73985c32a4b97acff83989288b9e; ?>
<?php unset($__componentOriginalb8f5c8a6ad1b73985c32a4b97acff83989288b9e); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::panel'); ?>
Welcome to NVP Animal Clinic! :) <br>
This is your password: <?php echo e($pass); ?>

<?php if (isset($__componentOriginal78a7183c9f5e577b074162f44312b5a9c6fd7b4c)): ?>
<?php $component = $__componentOriginal78a7183c9f5e577b074162f44312b5a9c6fd7b4c; ?>
<?php unset($__componentOriginal78a7183c9f5e577b074162f44312b5a9c6fd7b4c); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>

Thanks,<br>
NVP Animal Clinic
<?php if (isset($__componentOriginal2dab26517731ed1416679a121374450d5cff5e0d)): ?>
<?php $component = $__componentOriginal2dab26517731ed1416679a121374450d5cff5e0d; ?>
<?php unset($__componentOriginal2dab26517731ed1416679a121374450d5cff5e0d); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\wamp64\www\nvp-app\resources\views/emails/client/created.blade.php ENDPATH**/ ?>