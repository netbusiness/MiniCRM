@extends('layouts.app')

@section('breadcrumbs')
<li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Companies</a></li>
<li class="nav-item"><a href="#" class="nav-link disabled">Managers</a></li>
@endsection

@section('content')
<manager-home></manager-home>
@endsection
