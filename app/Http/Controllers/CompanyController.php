<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
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
        if (\Auth::user()->isAdmin()) {
            if ($request->has("all") && !!$request->input("all")) {
                return response(Company::all());
            } else {
                return response(Company::paginate(10));
            }
        } else {
            return response(\Auth::user()->companies()->paginate(10));
        }
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
            "email" => "nullable|email",
            "logo" => "nullable|image|dimensions:min_width=100,min_height=100",
            "website" => "nullable|url"
        ]);
        
        $logoPath = Storage::putFile("public", $data['logo']);
        
        $data['logo'] = str_replace("public", "storage", $logoPath);
        
        return response(Company::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            "name" => "required",
            "email" => "nullable|email",
            // "logo" => "nullable|image|dimensions:min_width=100,min_height=100",
            "website" => "nullable|url"
        ]);
        
        $company->update(\Arr::except($data, ['logo']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->managers()->detach();
        $company->employees()->delete();
        $company->delete();
        
        return response("{}", 200);
    }
}
