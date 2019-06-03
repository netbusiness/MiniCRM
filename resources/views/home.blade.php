@extends('layouts.app')

@section('breadcrumbs')
<li class="nav-item"><a class="nav-link disabled" href="#">Companies</a></li>
@endsection

@section('content')
<company-home :user-data='@json($userData)'></company-home>
@endsection
