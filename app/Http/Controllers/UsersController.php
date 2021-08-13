<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    
    public function viewAdmin(){

        return view('admin.dashboard');

    }

    public function viewClient($email = null){    
        
        // admin can find any user
        // but other user can only find themselves        

        if(!empty($email)){            
           
            if(auth()->user()->isAdmin() || auth()->user()->email == $email){
                
                if(User::where('email', $email)->where('user_type', 0)->exists()){                                         

                    $user = User::where('email', $email)->first();

                    return view('client.dashboard')->with('user', $user);

                } else {
                    
                    return redirect()->back();

                }                        


            } else {
                return redirect()->back();
            }
            
        } else {

            if(auth()->user()->isAdmin()){

                return redirect('/admin');
                
            } else {

                return view('client.dashboard')->with('user', Auth::user());

            }

        }



    }

   


}
