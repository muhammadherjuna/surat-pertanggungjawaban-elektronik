@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard SPJ Elektronik</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Selamat Datang</h3>
        </div>
        <div class="card-body">
            Halo <strong>{{ Auth::user()->name }}</strong>, Anda login sebagai <strong>{{ Auth::user()->role->name }}</strong>.
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
