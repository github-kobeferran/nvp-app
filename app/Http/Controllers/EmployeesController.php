<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\EmployeeCreated;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;

class EmployeesController extends Controller
{
 
    public function view(){

        if(is_null(auth()->user()->employee))
            return view('admin.view.employees');
        else
            return redirect()->back();

    }

    public function store(Request $request){

        $before_date = \Carbon\Carbon::now()->subYears(13); 
        $after_date = new \Carbon\Carbon('1941-01-01');

        if($request->method() != 'POST')
            return redirect()->back();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',         
            'middle_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',         
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',         
            'dob' => 'date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'contact' => 'digits:11',
            'address' => 'max:100',           
        ],[
            'dob.date' => 'invalid Date of Birth format',
            'dob.before' => 'Date of Birth must be before ' . $before_date->isoFormat('MMM DD, OY'),
            'dob.after' => 'Date of Birth must be after ' . $after_date->isoFormat('MMM DD, OY'),
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();                         
        
        $pass = \App\Models\Client::generateRandomString();

        $user = User::create([
            'last_name' =>  $request->input('last_name'),
            'middle_name' => $request->input('middle_name'),
            'first_name' =>  $request->input('first_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($pass),
            'user_type' => 1,
        ]); 

        $employee = new Employee;        

        $employee->user_id = $user->id;
        $employee->dob = $request->input('dob');
        $employee->sex = $request->input('sex');
        $employee->address = $request->input('address');
        $employee->contact = $request->input('contact');           

        $employee->save();

        Mail::to($user)->send(new EmployeeCreated($employee->user->first_name . ' ' . $employee->user->last_name, $pass));

        return redirect('/admin/employees')->with('success', 'Employee ' .  $employee->user->first_name . ' ' . $employee->user->last_name . ' added.');

    }

    public function update(Request $request){
        

        $before_date = \Carbon\Carbon::now()->subYears(13); 
        $after_date = new \Carbon\Carbon('1941-01-01');

        if($request->method() != 'POST')
        return redirect()->back();     

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',         
            'middle_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',         
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',         
            'dob' => 'date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'contact' => 'digits:11',
            'address' => 'max:100',           
        ]);    

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();                         
        }

        $employee = Employee::find($request->input('id'));

        $oldname = $employee->user->first_name . ' ' . $employee->user->last_name;

        $user = User::find($employee->user_id);

        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        
        $employee->dob = $request->input('dob');
        $employee->sex = $request->input('sex');
        $employee->address = $request->input('address');
        $employee->contact = $request->input('contact');

        $employee->save();
        $user->save();

        return redirect('/admin/employees')->with('info', 'Employee ' .  $oldname . ' has been successfully updated.');

        

    }

    public function delete(Request $request){

        if($request->method() != 'POST')
        return redirect()->back();  

        $employee = Employee::find($request->input('id'));
        
        $oldname = $employee->name;

        $employee->delete();

        return redirect('/admin/employees')->with('info', 'Employee ' .  $oldname . ' has been deleted.');

    }


}
