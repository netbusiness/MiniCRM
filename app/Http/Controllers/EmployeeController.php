<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            "company_id" => [
                "exists:companies,id,deleted_at,NULL",
                Rule::requiredIf(!\Auth::user()->isAdmin())
            ]
        ]);
        // TODO: Fix this
        /* if (\Auth::user()->isAdmin()) {
            return response(Employee::all());
        } else {
            return response(\Auth::user()->companies()->with("employees")->get());
            // return response(\Auth::user()->companies()->employees);
        } */
        return response(Employee::where("company_id", $data['company_id'])->paginate(10));
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
            "first_name" => "required",
            "last_name" => "required",
            "company_id" => "required|exists:companies,id,deleted_at,NULL",
            "email" => "nullable|email",
            "phone" => "nullable|max:30"
        ]);
        
        return response(Employee::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            "first_name" => "required",
            "last_name" => "required",
            "company_id" => "required|exists:companies,id,deleted_at,NULL",
            "email" => "nullable|email",
            "phone" => "nullable|max:30"
        ]);
        
        if (!\Auth::user()->isAdmin()) {
            // Security check
            $authorizedCompanies = \Auth::user()->companies()->get();
            
            if (!$authorizedCompanies->contains($employee->company_id)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "company_id" => "You are not allowed to modify this employee"
                ]);
            }
        }
        
        $employee->update(\Arr::except($data, ['company_id']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return response("{}", 200);
    }
}
