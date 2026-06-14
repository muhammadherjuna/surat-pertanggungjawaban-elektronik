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
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th style="width: 35%" class="text-left">Pengaju</th>
                            <td style="width: 1%">:</td>
                            <td class="text-left font-weight-bold">{{ $spj->user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Deskripsi</th>
                            <td>:</td>
                            <td class="text-left">{{ $spj->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Jenis SPJ</th>
                            <td>:</td>
                            <td class="text-left">{{ $spj->jenisSpj->nama_jenis }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Nominal</th>
                            <td>:</td>
                            <td class="text-left font-monospace text-nowrap">Rp {{ number_format($spj->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Tipe / No</th>
                            <td>:</td>
                            <td class="text-left">{{ trim($spj->filter_tipe . ' ' . $spj->filter_no) }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Rekening</th>
                            <td>:</td>
                            <td class="text-left">{{ $spj->rekening->kode_rekening }} - {{ $spj->rekening->nama_rekening }}</td>
                        </tr>
                        <tr>
                            <th class="text-left">Tanggal Diajukan</th>
                            <td>:</td>
                            <td class="text-left text-muted">{{ $spj->submitted_at ? $spj->submitted_at->format('d/m/Y H:i') : $spj->created_at->format('d/m/Y H:i') }}</td>
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
