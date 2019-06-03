@extends('layouts.app')

@section('breadcrumbs')
<li class="nav-item"><a class="nav-link disabled" href="#">Companies</a></li>
@accessLevel('admin')
<li class="nav-item"><a href="{{ url('/home/managers') }}" class="nav-link">Managers</a></li>
@endaccessLevel
@endsection

@section('content')
<company-home :user-data='@json($userData)'></company-home>
@endsection
