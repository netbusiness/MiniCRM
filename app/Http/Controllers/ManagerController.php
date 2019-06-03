<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            abort_unless($request->user()->isAdmin(), 403);
            
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::where("access_level", User::ACCESS_LEVEL_MANAGER)->with("companies")->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required|min:8",
            "companies" => "array"
        ]);
        
        $user = new User([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => \Hash::make($data['password']),
            "access_level" => User::ACCESS_LEVEL_MANAGER
        ]);
        
        $user->save();
        
        $user->companies()->sync($data['companies']);
        
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email," . $id,
            // "password" => "required|min:8|confirmed",
            // "password_confirmation" => "required|min:8",
            "companies" => "array"
        ]);
        
        $user = User::find($id);
        
        $user->update([
            "name" => $data['name'],
            "email" => $data['email'],
            // "password" => \Hash::make($data['password']),
            // "access_level" => User::ACCESS_LEVEL_MANAGER
        ]);
        
        $user->companies()->sync($data['companies']);
        
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        
        $user->companies()->detach();
        
        $user->delete();
    }
}
