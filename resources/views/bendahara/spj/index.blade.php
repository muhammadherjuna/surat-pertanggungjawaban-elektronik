@extends('adminlte::page')

@section('title', 'Dashboard Bendahara')

@section('content_header')
    <h1 class="mb-4">Dashboard Bendahara</h1>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">SPJ Menunggu Verifikasi</h5>
                    <h3>{{ $stats['total_masuk'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">SPJ Selesai (Tercairkan)</h5>
                    <h3>{{ $stats['total_selesai'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar SPJ</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Pengaju</th>
                            <th>Deskripsi</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $spj)
                            <tr>
                                <td>{{ $spj->user->name }}</td>
                                <td>{{ $spj->deskripsi }}</td>
                                <td>Rp {{ number_format($spj->nominal, 2, ',', '.') }}</td>
                                <td>
                                    @if($spj->status_level == 3)
                                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                    @elseif($spj->status_level == 4)
                                        <span class="badge bg-success">Selesai / Terverifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bendahara.spj.show', $spj) }}" class="btn btn-sm btn-info">Detail</a>
                                    @if($spj->status_level == 4)
                                        <a href="{{ route('bendahara.spj.print', $spj) }}" target="_blank" class="btn btn-sm btn-secondary">Cetak PDF</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada SPJ di tahap ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
