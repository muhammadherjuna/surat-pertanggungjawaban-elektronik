@extends('adminlte::page')

@section('title', 'Daftar SPJ Saya')

@section('content_header')
    <h1>Daftar SPJ Saya</h1>
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
        <div class="card-header">
            <a href="{{ route('operator.spj.create') }}" class="btn btn-primary">Buat SPJ Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID / UUID</th>
                            <th>Deskripsi</th>
                            <th>Jenis SPJ</th>
                            <th>Nominal</th>
                            <th>Status Level</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $spj)
                            <tr>
                                <td>{{ $spj->id }}<br><small class="text-muted">{{ $spj->uuid }}</small></td>
                                <td>{{ $spj->deskripsi }}</td>
                                <td>{{ $spj->jenisSpj->nama_jenis }}</td>
                                <td>Rp {{ number_format($spj->nominal, 2, ',', '.') }}</td>
                                <td>
                                    @if($spj->status_level == 0)
                                        <span class="badge bg-secondary">Draft / Diajukan</span>
                                    @elseif($spj->status_level == 1)
                                        <span class="badge bg-info text-dark">Disetujui Kabid</span>
                                    @elseif($spj->status_level == 2)
                                        <span class="badge bg-primary">Disetujui Sekdin</span>
                                    @elseif($spj->status_level == 3)
                                        <span class="badge bg-warning text-dark">Disetujui Kadin</span>
                                    @elseif($spj->status_level == 4)
                                        <span class="badge bg-success">Terverifikasi / Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($spj->is_rejected)
                                        <span class="badge bg-danger">Ditolak / Revisi</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('operator.spj.show', $spj) }}" class="btn btn-sm btn-info">Detail</a>
                                    @if($spj->status_level == 0 || $spj->is_rejected)
                                        <a href="{{ route('operator.spj.edit', $spj) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('operator.spj.destroy', $spj) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus SPJ ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada SPJ yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
