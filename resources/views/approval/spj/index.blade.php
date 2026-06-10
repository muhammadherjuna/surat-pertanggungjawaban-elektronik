@extends('adminlte::page')

@section('title', 'Daftar SPJ Butuh Persetujuan')

@section('content_header')
    <h1>Daftar SPJ Butuh Persetujuan</h1>
@stop

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Pengaju</th>
                            <th>Deskripsi</th>
                            <th>Jenis SPJ</th>
                            <th>Nominal</th>
                            <th>Tanggal Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $spj)
                            <tr>
                                <td>{{ $spj->user->name }}</td>
                                <td>{{ $spj->deskripsi }}</td>
                                <td>{{ $spj->jenisSpj->nama_jenis }}</td>
                                <td>Rp {{ number_format($spj->nominal, 2, ',', '.') }}</td>
                                <td>{{ $spj->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('approval.spj.show', $spj) }}" class="btn btn-sm btn-info">Detail & Evaluasi</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada SPJ yang butuh persetujuan saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
