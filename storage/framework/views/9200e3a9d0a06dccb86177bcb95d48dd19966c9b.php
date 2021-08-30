

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(empty(\App\Models\Service::all())): ?>
        
    <?php else: ?>

        <div class="container text-center">

            <h1>Our Services</h1> 

            <hr>
            
            <table id="services" class="table table-bordered">
                <thead class="bg-info">
                    <tr>
                        <th>Description</th>                                                
                        <th>Fee</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = \App\Models\Service::orderBy('desc', 'asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(ucfirst($service->desc)); ?></td>
                        <td>&#8369; <?php echo e($service->price); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                </tbody>                
            </table>

        </div>

    <?php endif; ?>


<script>

$(document).ready( function () {
    $('#services').DataTable();
} );

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/services.blade.php ENDPATH**/ ?>