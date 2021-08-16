<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeesController extends Controller
{
 
    public function view(){

        return view('admin.view.employees');

    }

    public function store(Request $request){

        $before_date = \Carbon\Carbon::now()->subYears(13); 
        $after_date = new \Carbon\Carbon('1941-01-01');

        if($request->method() != 'POST')
            return redirect()->back();

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',         
            'dob' => 'date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'contact' => 'digits_between:10,15',
            'address' => 'max:100',           
        ]);    

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();                         
        }

        $employee = new Employee;        

        $employee->name = $request->input('name');
        $employee->dob = $request->input('dob');
        $employee->sex = $request->input('sex');
        $employee->address = $request->input('address');
        $employee->contact = $request->input('contact');

        $employee->save();

        return redirect('/admin/employees')->with('success', 'Employee ' .  $employee->name  . ' added.');

    }

    public function update(Request $request){

        $before_date = \Carbon\Carbon::now()->subYears(13); 
        $after_date = new \Carbon\Carbon('1941-01-01');

        if($request->method() != 'POST')
        return redirect()->back();     

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',         
            'dob' => 'date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'contact' => 'digits_between:10,15',
            'address' => 'max:100',           
        ]);    

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();                         
        }

        $employee = Employee::find($request->input('id'));

        $oldname = $employee->name;

        $employee->name = $request->input('name');
        $employee->dob = $request->input('dob');
        $employee->sex = $request->input('sex');
        $employee->address = $request->input('address');
        $employee->contact = $request->input('contact');

        $employee->save();

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
