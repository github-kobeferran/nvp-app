<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function showInventory($orderBy = 'quantity', $sortBy = 'desc'){         
                 
        return view('inventory')->with('the_items', Item::orderBy($orderBy, $sortBy)->get())
                                ->with('orderBy', $orderBy)
                                ->with('sortBy', $sortBy);        
        
    }

    public function showServices(){

        return view('services');

    }

}
