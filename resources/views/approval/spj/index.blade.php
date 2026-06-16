@extends('adminlte::page')

@section('title', 'Daftar SPJ Butuh Persetujuan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar SPJ Butuh Persetujuan</h1>
    </div>
@stop

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-muted font-weight-bold">
                <i class="fas fa-tasks text-primary mr-2"></i> Antrean Persetujuan SPJ
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th style="width: 5%;" class="text-center align-middle">No</th>
                            <th style="width: 15%;" class="text-left align-middle">Pengaju</th>
                            <th style="width: 30%;" class="text-left align-middle">Deskripsi</th>
                            <th style="width: 15%;" class="text-left align-middle">Jenis SPJ</th>
                            <th style="width: 15%;" class="text-left align-middle">Nominal</th>
                            <th style="width: 12%;" class="text-left align-middle">Tanggal Diajukan</th>
                            <th style="width: 8%;" class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $index => $spj)
                            <tr>
                                <td class="text-center text-muted align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle text-left font-weight-bold">{{ $spj->user->name }}</td>
                                <td class="align-middle text-left text-wrap">{{ $spj->deskripsi }}</td>
                                <td class="align-middle text-left">{{ $spj->jenisSpj->nama_jenis }}</td>
                                <td class="align-middle text-left font-monospace text-nowrap">
                                    Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                </td>
                                <td class="align-middle text-left text-nowrap text-muted">
                                    {{ $spj->submitted_at ? $spj->submitted_at->format('d/m/Y H:i') : $spj->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('approval.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail & Evaluasi">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                                    Semua beres! Tidak ada SPJ yang butuh persetujuan saat ini.
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

@section('js')
<script>
    // Auto-hide notifikasi setelah 4 detik
    setTimeout(function() {
        $('.auto-close').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 4000);
</script>
@stop
