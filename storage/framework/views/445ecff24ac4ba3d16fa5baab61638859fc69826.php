

<?php echo $__env->make('inc.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(empty(\App\Models\Item::all())): ?>
        
    <?php else: ?>

        <div class="container text-center">

            <h1>Our Products</h1> 

            <hr>
            
            <table id="products" class="table table-bordered">
                <thead class="bg-info">
                    <tr>
                        <th>Description</th>
                        <th>Category</th>
                        <th>For</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = \App\Models\Item::orderBy('desc', 'asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(ucfirst($item->desc)); ?></td>
                        <td><?php echo e(strtoupper($item->category->desc)); ?></td>
                        <td><?php echo e(is_null($item->pet_type_id) ? 'For All Pet Types' : ucfirst($item->type->type)); ?></td>
                        <td>&#8369; <?php echo e($item->reg_price); ?></td>
                        <td><?php echo e($item->pet_type_id ? 'Available' : 'Out of Stock'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                </tbody>                
            </table>

        </div>

    <?php endif; ?>


<script>

$(document).ready( function () {
    $('#products').DataTable();
} );

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/inventory.blade.php ENDPATH**/ ?>