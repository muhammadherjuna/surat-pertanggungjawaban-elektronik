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

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-muted font-weight-bold">
                <i class="fas fa-list text-primary mr-2"></i> Daftar SPJ
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th style="width: 5%;" class="text-center align-middle">No</th>
                            <th style="width: 15%;" class="text-left align-middle">Pengaju</th>
                            <th style="width: 25%;" class="text-left align-middle">Deskripsi</th>
                            <th style="width: 15%;" class="text-left align-middle">Nominal</th>
                            <th style="width: 15%;" class="text-left align-middle">Tanggal Diajukan</th>
                            <th style="width: 15%;" class="text-left align-middle">Status</th>
                            <th style="width: 10%;" class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $index => $spj)
                            <tr>
                                <td class="text-center text-muted align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle text-left font-weight-bold">{{ $spj->user->name }}</td>
                                <td class="align-middle text-left text-wrap">{{ $spj->deskripsi }}</td>
                                <td class="align-middle text-left font-monospace text-nowrap">
                                    Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                </td>
                                <td class="align-middle text-left text-nowrap text-muted">
                                    {{ $spj->submitted_at ? $spj->submitted_at->format('d/m/Y H:i') : $spj->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="align-middle text-left">
                                    @if($spj->status_level == 3)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock mr-1"></i>Menunggu Verifikasi</span>
                                    @elseif($spj->status_level == 4)
                                        <span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i>Selesai / Terverifikasi</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center" style="gap: 6px;">
                                        <a href="{{ route('bendahara.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($spj->status_level == 4)
                                            <a href="{{ route('bendahara.spj.print', $spj) }}" target="_blank" class="btn btn-sm btn-secondary shadow-sm font-weight-bold" title="Cetak PDF">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    Tidak ada SPJ di tahap ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
