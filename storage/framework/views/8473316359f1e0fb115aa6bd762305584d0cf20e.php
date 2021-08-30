<nav class="navbar navbar-expand-md navbar-light border ">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(url('storage/images/system/icons/nvp-logo-pic.jpg')); ?>" alt="" style="max-width: 55px;" class="img-fluid">
            <span class="ml-2">NVP ANIMAL CLINIC </span>
        </a>
    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <?php if(auth()->guard()->guest()): ?>
                    <?php if(Route::has('login')): ?>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('LOGIN')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if(Route::has('register')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('REGISTER')); ?></a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <?php echo e(Auth::user()->name); ?>

                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            <?php if(auth()->user()->isAdmin()): ?>
                            
                                <a class="dropdown-item" href="<?php echo e(route('user.admin')); ?>"                                >
                                    <?php echo e(auth()->user()->name); ?>

                                </a>
                            
                            <?php else: ?>

                                <a class="dropdown-item" href="/user">
                                    <?php echo e(auth()->user()->name); ?>

                                </a>

                            <?php endif; ?>


                            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <?php echo e(__('Logout')); ?>

                            </a>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                <?php echo csrf_field(); ?>
                            </form>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav><?php /**PATH D:\wamp64\www\nvp-app\resources\views/inc/nav.blade.php ENDPATH**/ ?>