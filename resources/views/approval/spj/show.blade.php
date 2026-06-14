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
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th style="width: 35%;" class="text-left align-middle">Nama Dokumen</th>
                                        <th style="width: 25%;" class="text-center align-middle">File / Status</th>
                                        <th style="width: 40%;" class="text-left align-middle">Komentar Revisi (Isi jika salah)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($spj->jenisSpj->dokumenPendukungs as $dp)
                                        @php
                                            $uploadedDokumen = $spj->dokumens->firstWhere('dokumen_pendukung_id', $dp->id);
                                        @endphp
                                        <tr>
                                            <td class="align-middle text-left font-weight-bold">
                                                {{ $dp->nama_dokumen }}
                                                @if($dp->is_wajib)
                                                    <span class="text-danger" title="Wajib">*</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($uploadedDokumen)
                                                    <div class="d-flex flex-column align-items-center" style="gap: 5px;">
                                                        <span class="badge bg-success mb-1"><i class="fas fa-check-circle mr-1"></i>Sudah Diunggah</span>
                                                        <a href="{{ asset('storage/' . $uploadedDokumen->file_path) }}" target="_blank" class="btn btn-xs btn-info text-white shadow-sm font-weight-bold">
                                                            <i class="fas fa-eye mr-1"></i> Buka File
                                                        </a>
                                                    </div>
                                                @else
                                                    @if($dp->is_wajib)
                                                        <span class="badge bg-danger"><i class="fas fa-exclamation-circle mr-1"></i>Belum Diunggah (Wajib)</span>
                                                    @else
                                                        <span class="badge bg-secondary"><i class="fas fa-clock mr-1"></i>Belum Diunggah (Opsional)</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="align-middle text-left">
                                                @if($uploadedDokumen)
                                                    <textarea name="komentar[{{ $uploadedDokumen->id }}]" class="form-control form-control-sm" rows="2" placeholder="Alasan penolakan dokumen ini..."></textarea>
                                                @else
                                                    @if($dp->is_wajib)
                                                        <p class="text-danger small mb-0"><i class="fas fa-exclamation-triangle mr-1"></i> Dokumen wajib ini belum ada. Anda bisa menolak SPJ karena kelengkapan.</p>
                                                    @else
                                                        <p class="text-muted small mb-0"><i class="fas fa-info-circle mr-1"></i> Dokumen opsional tidak wajib diunggah.</p>
                                                    @endif
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
