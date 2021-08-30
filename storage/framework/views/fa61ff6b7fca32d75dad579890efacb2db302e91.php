



<?php $__env->startSection('content'); ?>

<?php echo $__env->make('inc.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container">

    <h1 class="m-2 bg-info text-white border border-primary text-center rounded">ADMIN DASHBOARD</h1>

    <div class="row">

        <div class="col">
            
            <a href="<?php echo e(url('/admin/clients')); ?>" class="btn btn-secondary btn-block">
                <?php if(empty(\App\Models\Client::first() )): ?>
                No Clients
                <?php else: ?>
                Client<?php echo e(\App\Models\Client::count() > 0 ? 's' : ''); ?> <span class="badge badge-light"><?php echo e(\App\Models\Client::count()); ?></span>
                <?php endif; ?>
            </a>

        </div>

        <div class="col">

            <a href="<?php echo e(url('/admin/pets')); ?>"  class="btn btn-danger btn-block">
                <?php if(empty(\App\Models\Pet::first() )): ?>
                No Pets
                <?php else: ?>
                Pet<?php echo e(\App\Models\Pet::count() > 0 ? 's' : ''); ?> <span class="badge badge-light"><?php echo e(\App\Models\Pet::count()); ?></span>
                <?php endif; ?>
            </a>

        </div>

        <div class="col">

            <a href="<?php echo e(url('/admin')); ?>" class="btn btn-primary btn-block">
                <?php if(empty(\App\Models\Appointment::all())): ?>
                No Appointments
                <?php else: ?>
                Appointment<?php echo e(\App\Models\Appointment::where('done', 0)->count() > 0 ? 's' : ''); ?> <span class="badge badge-light"><?php echo e(\App\Models\Appointment::count()); ?></span>
                <?php endif; ?>
            </a>

        </div>

    </div>

    

    <div class="container text-center my-5 py-2 border border-info">

        <?php if(session('status')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->make('inc.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <h2>Set an Appointment</h2>

        <?php echo Form::open(['url' => '/storeappointment']); ?>     
        <hr>   
            
        <div class="form-group">

            <label for="type">Client</label>

            <?php                     
                $users = \App\Models\User::where('user_type', 0)->get();                    

                foreach ($users as $user) {
                    $user->client;
                }                        

                $user_clients = collect();
                
                foreach ($users as $user) {

                    $user_clients->push(collect(['name' => $user->name, 'id' => $user->client->id]));

                }                
                
                $list = $user_clients->pluck('name', 'id');
                $list->all();

            ?>        
             <?php echo e(Form::select('client_id', $list , null, ['id' => 'clientSelect', 'title' => 'Select Client', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])); ?>


        </div>   
        
        <div class="form-group">
        
            <label for="type">Pet</label>   
        
            <?php echo e(Form::select('pet_id', [] , null, ['id' => 'petSelect', 'class' => 'form-control w-25 ml-2 border mx-auto', 'required' => 'required'])); ?>

        
        </div>  

        <div class="form-group">

            <label for="type">Service</label>

            <?php                     
         
                $services = \App\Models\Service::orderBy('desc', 'asc')->get()->pluck('desc', 'id');                

            ?>   

            <?php echo e(Form::select('service_id', $services , null, ['title' => 'Select Service', 'data-live-search' => 'true', 'class' => 'selectpicker ml-2 border', 'style' => 'font-size: 1.2rem;', 'required' => 'required'])); ?>


        </div>  


        <hr>

        <div class="form-group">
            <label for="">From:</label>
            <div class="input-group date w-50 mx-auto" id="datetimepicker1" data-target-input="nearest">
                <input type="text" name="start_time"class="form-control datetimepicker-input" data-target="#datetimepicker1" required/>
                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="">To:</label>
            <div class="input-group date w-50 mx-auto" id="datetimepicker2" data-target-input="nearest">
                <input type="text" name="end_time"class="form-control datetimepicker-input" data-target="#datetimepicker2" required/>
                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>

        <hr>

        <button class="btn btn-block btn-primary w-50 mx-auto">Set Appointment</button>
    

        <?php echo Form::close(); ?>



    </div>


    <?php if(empty(\App\Models\Appointment::first())): ?>
    
    <?php else: ?>
        
    <div class="container mt-2">
        
        <h3 class="mt-3 text-center " style="text-shadow: 1px 1px 10px rgb(112, 112, 112);">Appointments</h3>
        
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item border-bottom-0">
                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Today  <span class="badge badge-info text-white"> <?php echo e(\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->count()); ?></span></a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">This Week (<?php echo e(\Carbon\Carbon::now()->startOfWeek()->isoFormat('MMM DD') . '-' . \Carbon\Carbon::now()->endOfWeek()->isoFormat('MMM DD')); ?>)</a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Month of <?php echo e(\Carbon\Carbon::now()->monthName); ?></a>
            </li>
            <li class="nav-item border-bottom-0">
                <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">Appointment History</a>
            </li>
        </ul><!-- Tab panes -->


        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">

                <div class="container m-4">  

                    <?php if(empty(\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->first())): ?>

                    <b class="text-center">No Appointments Today</b>

                    <?php else: ?>

                        <table class="table table-bordered">
                            <thead class="bg-warning ">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Pet</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__currentLoopData = \App\Models\Appointment::whereBetween('start_time',
                                                                                [\Carbon\Carbon::now()->startOfDay(), 
                                                                                \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                                                                                [\Carbon\Carbon::now()->startOfDay(), 
                                                                                \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->orderBy('start_time','asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <tr>         
                                        <td><?php echo e($appointment->service->desc); ?></td>    

                                        <td><a href="<?php echo e(url('/user/'.$appointment->client->user->email )); ?>"><?php echo e($appointment->client->user->name); ?></a></td>
                                        

                                        <td><a href="<?php echo e(url('/pet/'.$appointment->client->user->email . '/' . $appointment->pet->name )); ?>"><?php echo e(ucfirst($appointment->pet->name)); ?></a></td>
                                        
                                        <td><?php echo e(\Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a')); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#markAppointment-<?php echo e($appointment->id); ?>">
                                                Mark as Done
                                            </button> 
                                        </td>
                                    </tr>  
                                    
                                    
                                    <div class="modal fade"  id="markAppointment-<?php echo e($appointment->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title " id="exampleModalLongTitle"><?php echo e($appointment->service->desc . ' : ' . $appointment->client->user->name); ?></h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">

                                                Client : <?php echo e(ucfirst($appointment->client->user->name)); ?>

                                                <br>
                                                Service : <?php echo e($appointment->service->desc); ?>

                                                <hr>
                                                Fee : <?php echo e($appointment->service->fee); ?>

                                                <hr>
                                                Mark this appointment as <b>DONE?</b>

                                            </div>
                                            <?php echo Form::open(['url' => '/appointmentdone']); ?>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                
                                                <?php echo e(Form::hidden('id', $appointment->id)); ?>

                                                <?php echo e(Form::hidden('pet_id', $appointment->pet->id)); ?>

                
                                                <button type="submit" class="btn btn-primary">Yes</button>
                
                                            <?php echo Form::close(); ?>

                                            </div>
                                        </div>
                                        </div>
                                    </div>
                            

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        
                    <?php endif; ?>
                    
                </div>
            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <div class="container m-4">
                    <?php if(empty(\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfWeek(), 
                    \Carbon\Carbon::now()->endOfWeek()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfWeek(), 
                    \Carbon\Carbon::now()->endOfWeek()])->where('done', 0)->first())): ?>

                    <b class="text-center">No Appointments this Week</b>

                    <?php else: ?>

                        <table class="table table-bordered">
                            <thead class="bg-warning ">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Time</th>                                                                        
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__currentLoopData = \App\Models\Appointment::whereBetween('start_time',
                                                                                [\Carbon\Carbon::now()->startOfWeek(), 
                                                                                \Carbon\Carbon::now()->endOfWeek()])->whereBetween('end_time',
                                                                                [\Carbon\Carbon::now()->startOfWeek(), 
                                                                                \Carbon\Carbon::now()->endOfWeek()])->where('done', 0)->orderBy('start_time','asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <tr>         
                                        <td><?php echo e($appointment->service->desc); ?></td>    

                                        <td><a href="<?php echo e(url('/user/'.$appointment->client->user->email )); ?>"><?php echo e($appointment->client->user->name); ?></a></td>
                                        
                                        <td><?php echo e(\Carbon\Carbon::parse($appointment->start_time)->isoFormat('MMM D, OY') . ' ' . \Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a')); ?></td>
                                       
                                    </tr>  
                                                                                                       

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane" id="tabs-3" role="tabpanel">
                <div class="container m-4">
                    <?php if(empty(\App\Models\Appointment::whereBetween('start_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->whereBetween('end_time',
                    [\Carbon\Carbon::now()->startOfDay(), 
                    \Carbon\Carbon::now()->endOfDay()])->where('done', 0)->first())): ?>

                    <b class="text-center">No Appointments Today</b>

                    <?php else: ?>

                        <table class="table table-bordered">
                            <thead class="bg-warning ">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__currentLoopData = \App\Models\Appointment::whereBetween('start_time',
                                                                                [\Carbon\Carbon::now()->startOfMonth(), 
                                                                                \Carbon\Carbon::now()->endOfMonth()])->whereBetween('end_time',
                                                                                [\Carbon\Carbon::now()->startOfMonth(), 
                                                                                \Carbon\Carbon::now()->endOfMonth()])->where('done', 0)->orderBy('start_time','asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <tr>         
                                        <td><?php echo e($appointment->service->desc); ?></td>    

                                        <td><a href="<?php echo e(url('/user/'.$appointment->client->user->email )); ?>"><?php echo e($appointment->client->user->name); ?></a></td>
                                        
                                        <td><?php echo e(\Carbon\Carbon::parse($appointment->start_time)->isoFormat('MMM D, OY') . ' ' . \Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a')); ?></td>
                                    </tr>                                                                    
                            

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab-pane" id="tabs-4" role="tabpanel">
                <div class="container m-4">
                    
                    <?php if(empty(\App\Models\Appointment::first())): ?>

                    <?php else: ?>

                        <i class="fa fa-history" aria-hidden="true"></i> Appointment History

                        <table id="appointment-history" class="table table-bordered">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Service</th>
                                    <th>Client</th>
                                    <th>Schedule</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__currentLoopData = \App\Models\Appointment::where('done', 1)->orderBy('updated_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <tr>         
                                        <td><?php echo e($appointment->service->desc); ?></td>    

                                        <td><a href="<?php echo e(url('/user/'.$appointment->client->user->email )); ?>"><?php echo e($appointment->client->user->name); ?></a></td>
                                        
                                        <td><?php echo e(\Carbon\Carbon::parse($appointment->start_time)->isoFormat('MMM D, OY') . ' ' . \Carbon\Carbon::parse($appointment->start_time)->isoFormat('h:mm a') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->isoFormat('h:mm a')); ?></td>
                                    
                                    </tr>  

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                        
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
        

    </div>
    <?php endif; ?>


</div>

<script>

let petSelect = document.getElementById('petSelect');
let clientSelect = document.getElementById('clientSelect');

clientSelect.addEventListener('change', () => {

    let client_id = clientSelect.value;

    let xhr = new XMLHttpRequest();        

    xhr.open('GET', APP_URL + '/getclientpets/' + client_id , true);

    xhr.onload = function() {
        if (this.status == 200) {                 
            
            let pets = JSON.parse(this.responseText);  
                                        
            
            let output = `<select name="pet_id" class="custom-select ml-2 border w-25 mx-auto" id="petSelect"   required>`;                                  
            for(let i in pets){
                output+=`<option value="`+ pets[i].id +`" >` + capitalizeFirstLetter(pets[i].name) +`</option>`;                        
            }
            output+=`</select>`;         
                    
            
            petSelect.innerHTML = output;
            
                        
        }              

    }

    xhr.send(); 

    

});


$(function() {
$('#datetimepicker1').datetimepicker({      
});
$('#datetimepicker2').datetimepicker();
});


$(document).ready( function () {
    // $('#appointment-history').DataTable(
    //     {
    //     "order": [[ 0, "desc" ]]
    // });
    $('#appointment-history').DataTable();
} );




</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\nvp-app\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>