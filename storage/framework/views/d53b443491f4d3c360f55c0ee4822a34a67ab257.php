<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    
    <img src="<?php echo e(url('storage/images/system/homepage-images/pexels-snapwire-46024.jpg')); ?>" class="img-fluid homepage-img" alt="Responsive image">

    <h1 class="homepage-slogan">Love Your Pet. Love Your Life.</h1>    
    
    <div class="card-deck my-4 text-center">
        <div class="card mb-4 box-shadow">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal">Something your pet need? We got it!</h4>
        </div>
        <div class="card-body">            
            <ul class="list-unstyled mt-3 mb-4">

                <?php if(empty(\App\Models\ItemCategory::all())): ?>
                    
                <?php else: ?>

                    <?php $__currentLoopData = \App\Models\ItemCategory::orderBy('id', 'desc')->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <li><?php echo e(strtoupper($category->desc)); ?></li>           
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              

                <?php endif; ?>
            
            </ul>
            <a href="/inventory" class="btn btn-lg btn-block btn-outline-primary">See more</a>
        </div>
        </div>
        <div class="card mb-4 box-shadow">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal">Provide the care your pet needs through our Services..</h4>
        </div>
        <div class="card-body">            
            <ul class="list-unstyled mt-3 mb-4">
                <?php if(empty(\App\Models\Service::all())): ?>
                    
                <?php else: ?>

                    <?php $__currentLoopData = \App\Models\Service::orderBy('created_at', 'desc')->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $services): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <li><?php echo e(ucfirst(strtolower($services->desc))); ?></li>           
                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php endif; ?>
            </ul>
            <a href="/services" class="btn btn-lg btn-block btn-primary">View Services</a>
        </div>
        </div>
       
    </div>

    <div class="text-center mx-auto">
        For Contact Tracing please scan this QR code
        <img src="<?php echo e(url('storage/images/system/homepage-images/qrcode.png')); ?>" alt="" class="img-thumbnail">
    </div>
                  
</div>

<?php echo $__env->make('inc.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/home.blade.php ENDPATH**/ ?>