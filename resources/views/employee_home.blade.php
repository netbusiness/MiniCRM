@extends('layouts.app')

@section('breadcrumbs')
<li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Companies</a></li>
<li class="nav-item"><a class="nav-link disabled" href="#">Employees</a></li>
@accessLevel('admin')
<li class="nav-item"><a href="{{ url('/home/managers') }}" class="nav-link">Managers</a></li>
@endaccessLevel
@endsection

@section('content')
<employee-home :user-data='@json($userData)' :company-id='{{ $companyId }}'></employee-home>
@endsection
