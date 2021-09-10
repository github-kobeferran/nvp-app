<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes(['verify' => true]);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/inventory', [App\Http\Controllers\HomeController::class, 'showInventory'])->name('home.inventory');
Route::get('/services', [App\Http\Controllers\HomeController::class, 'showServices'])->name('home.services');

Route::get('/user', [App\Http\Controllers\UsersController::class, 'viewClient'])->name('user.client')->middleware(['verified']);
Route::get('/user/{email?}', [App\Http\Controllers\UsersController::class, 'viewClient'])->name('user.client')->middleware(['verified']);

Route::get('/createpet', [App\Http\Controllers\PetsController::class, 'create'])->name('user.client')->middleware(['verified', 'client.updated']);
Route::get('/pet/{email}/{pet?}', [App\Http\Controllers\PetsController::class, 'show'])->name('pet.show')->middleware(['verified', 'client.updated']);
Route::get('/createpet/{email}', [App\Http\Controllers\PetsController::class, 'create'])->name('pet.create')->middleware(['verified', 'client.updated']);

Route::any('/registerpetadmin', [App\Http\Controllers\PetsController::class, 'store'])->name('pet.store');

Route::any('/reschedappointment', [App\Http\Controllers\AppointmentsController::class, 'updateSchedule'])->name('appointment.reschedule')->middleware(['verified', 'client.updated']);
Route::get('/transactions/{email?}', [App\Http\Controllers\ClientsController::class, 'viewTransactions'])->name('client.view.transactions')->middleware(['verified', 'client.updated']);
Route::get('/validateappointmentdate/{date}', [App\Http\Controllers\AppointmentsController::class, 'validateDate'])->name('appointment.date.validate')->middleware(['verified', 'client.updated']);


Route::middleware([App\Http\Middleware\ProtectAdminRoutesMiddleware::class])->group(function () {

    Route::get('/admin', [App\Http\Controllers\UsersController::class, 'viewAdmin'])->name('user.admin');
    Route::get('/admin/createclient', [App\Http\Controllers\ClientsController::class, 'create'])->name('client.create');
    Route::get('/admin/clients', [App\Http\Controllers\ClientsController::class, 'view'])->name('client.view');
    Route::any('/registerclient', [App\Http\Controllers\ClientsController::class, 'store'])->name('client.store');
    Route::get('/admin/pets', [App\Http\Controllers\PetsController::class, 'view'])->name('pet.view');
    Route::any('/storepettype', [App\Http\Controllers\PetTypesController::class, 'store'])->name('type.store');
    Route::any('/updatepettype', [App\Http\Controllers\PetTypesController::class, 'update'])->name('type.update');
    Route::any('/deletepettype', [App\Http\Controllers\PetTypesController::class, 'delete'])->name('type.delete');
    Route::get('/petdata/{id}', [App\Http\Controllers\PetTypesController::class, 'showData'])->name('type.data');
    Route::any('/admin/pettype/search/{txt?}', [App\Http\Controllers\PetTypesController::class, 'search'])->name('type.search');
    Route::get('/admin/inventory', [App\Http\Controllers\ItemsController::class, 'view'])->name('item.view');
    Route::any('/additem', [App\Http\Controllers\ItemsController::class, 'store'])->name('item.store');
    Route::any('/deleteitem', [App\Http\Controllers\ItemsController::class, 'delete'])->name('item.delete');
    Route::any('/updateitem', [App\Http\Controllers\ItemsController::class, 'update'])->name('item.update');
    Route::any('/deletecat', [App\Http\Controllers\ItemCategoriesController::class, 'delete'])->name('category.delete');
    Route::any('/updatecat', [App\Http\Controllers\ItemCategoriesController::class, 'update'])->name('category.update');
    Route::any('/addcat', [App\Http\Controllers\ItemCategoriesController::class, 'store'])->name('category.store');
    Route::get('/admin/services', [App\Http\Controllers\ServicesController::class, 'view'])->name('service.view');
    Route::any('/updateservice', [App\Http\Controllers\ServicesController::class, 'update'])->name('service.update');
    Route::any('/deleteservice', [App\Http\Controllers\ServicesController::class, 'delete'])->name('service.delete');
    Route::any('/storeservice', [App\Http\Controllers\ServicesController::class, 'store'])->name('service.store');
    Route::any('/appointmentdone', [App\Http\Controllers\AppointmentsController::class, 'done'])->name('appointment.done');
    Route::any('/storeappointment', [App\Http\Controllers\AppointmentsController::class, 'store'])->name('appointment.store');
    Route::any('/outofstock', [App\Http\Controllers\ItemsController::class, 'noStock'])->name('item.noStock');
    Route::any('/itemexport', [App\Http\Controllers\ItemsController::class, 'export'])->name('item.export');
    Route::any('/admin/employees', [App\Http\Controllers\EmployeesController::class, 'view'])->name('employee.view');
    Route::any('/employeeupdate', [App\Http\Controllers\EmployeesController::class, 'update'])->name('employee.update');
    Route::any('/employeedelete', [App\Http\Controllers\EmployeesController::class, 'delete'])->name('employee.delete');
    Route::any('/employeestore', [App\Http\Controllers\EmployeesController::class, 'store'])->name('employee.store');
    Route::get('/clientsexport', [App\Http\Controllers\ClientsController::class, 'export'])->name('client.export');
    Route::get('/getclientpets/{id}', [App\Http\Controllers\ClientsController::class, 'getRemainingPets'])->name('client.pets');
    Route::any('/updatesetting', [App\Http\Controllers\SettingsController::class, 'update'])->name('setting.update');    
    Route::any('/abadonappointment', [App\Http\Controllers\AppointmentsController::class, 'abandon'])->name('appointment.reschedule');

});

Route::middleware([App\Http\Middleware\ProtectClientRoutesMiddleware::class])->group(function () {

    Route::get('/edituser', [App\Http\Controllers\ClientsController::class, 'edit'])->name('client.edit');
    Route::any('/updateuser', [App\Http\Controllers\ClientsController::class, 'update'])->name('client.update');
    Route::get('/editpet/{name}', [App\Http\Controllers\PetsController::class, 'edit'])->name('pet.edit');
    Route::any('/updatepet', [App\Http\Controllers\PetsController::class, 'update'])->name('pet.update');    
    Route::get('/gettotalservicefee/{ids}', [App\Http\Controllers\ServicesController::class, 'totalFee'])->name('service.totalFee');    
    Route::get('/setappointment/{petID}/{date}/{services}', [App\Http\Controllers\AppointmentsController::class, 'clientStore'])->name('appointment.clientstore');    
    Route::get('/makeorderclient/{clientid}/{itemid}/{quantity}', [App\Http\Controllers\OrdersController::class, 'clientStore'])->name('order.clientStore');    

});


