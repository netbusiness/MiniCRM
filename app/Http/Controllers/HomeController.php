<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('home', ["userData" => \Auth::user()]);
    }
    
    /**
     * Show the employees in this company and potentially edit them
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeeIndex($id) {
       return view("employee_home", ["userData" => \Auth::user(), "companyId" => $id]); 
    }
}
