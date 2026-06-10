@extends('adminlte::page')

@section('title', 'Evaluasi SPJ')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Evaluasi SPJ</h1>
        <a href="{{ route('approval.spj.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi SPJ</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 35%">Pengaju</th>
                            <td>: {{ $spj->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>: {{ $spj->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th>Jenis SPJ</th>
                            <td>: {{ $spj->jenisSpj->nama_jenis }}</td>
                        </tr>
                        <tr>
                            <th>Nominal</th>
                            <td>: Rp {{ number_format($spj->nominal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Tipe / No</th>
                            <td>: {{ $spj->filter_tipe }} {{ $spj->filter_no ? ' / ' . $spj->filter_no : '' }}</td>
                        </tr>
                        <tr>
                            <th>Rekening</th>
                            <td>: {{ $spj->rekening->kode_rekening }} - {{ $spj->rekening->nama_rekening }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($spj->status_level == $targetLevel && !$spj->is_rejected)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tindakan</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Jika semua dokumen lengkap dan benar, silakan klik Setujui. Jika ada yang kurang/salah, isikan komentar revisi pada tabel dokumen lalu klik Tolak.</p>
                    
                    <form action="{{ route('approval.spj.approve', $spj) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin menyetujui SPJ ini?');">
                        @csrf
                        <button type="submit" class="btn btn-success me-2">Setujui SPJ</button>
                    </form>

                    <button type="button" class="btn btn-danger" onclick="document.getElementById('reject-form').submit();">Tolak SPJ (Revisi)</button>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Evaluasi Dokumen Pendukung</h5>
                </div>
                <div class="card-body">
                    <form id="reject-form" action="{{ route('approval.spj.reject', $spj) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menolak SPJ ini dan mengembalikannya ke Operator?');">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Dokumen</th>
                                        <th>File</th>
                                        <th>Komentar Revisi (Isi jika salah)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($spj->jenisSpj->dokumenPendukungs as $dp)
                                        @php
                                            $uploadedDokumen = $spj->dokumens->firstWhere('dokumen_pendukung_id', $dp->id);
                                        @endphp
                                        <tr>
                                            <td>{{ $dp->nama_dokumen }}</td>
                                            <td>
                                                @if($uploadedDokumen)
                                                    <a href="{{ asset('storage/' . $uploadedDokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Buka File</a>
                                                @else
                                                    <span class="badge bg-danger">Belum Diunggah</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($uploadedDokumen)
                                                    <textarea name="komentar[{{ $uploadedDokumen->id }}]" class="form-control" rows="2" placeholder="Alasan penolakan dokumen ini..."></textarea>
                                                @else
                                                    <p class="text-danger small mb-0">Dokumen belum ada. Anda bisa menolak SPJ dengan alasan dokumen tidak lengkap.</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
