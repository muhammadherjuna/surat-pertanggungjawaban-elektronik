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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        {{-- ===================== --}}
        {{-- KOLOM KIRI: Info SPJ  --}}
        {{-- ===================== --}}
        <div class="col-md-4">
            {{-- Card Info SPJ --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-file-invoice text-primary mr-2"></i>Informasi SPJ</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-borderless table-sm mb-0">
                        <tr class="border-bottom">
                            <td class="text-muted pl-3" style="width: 38%; padding-top: 10px; padding-bottom: 10px;">Pengaju</td>
                            <td class="font-weight-bold" style="padding-top: 10px; padding-bottom: 10px;">{{ $spj->user->name }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted pl-3" style="padding-top: 10px; padding-bottom: 10px;">Deskripsi</td>
                            <td style="padding-top: 10px; padding-bottom: 10px; line-height: 1.5;">{{ $spj->deskripsi }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted pl-3" style="padding-top: 10px; padding-bottom: 10px;">Jenis SPJ</td>
                            <td style="padding-top: 10px; padding-bottom: 10px;">{{ $spj->jenisSpj->nama_jenis }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted pl-3" style="padding-top: 10px; padding-bottom: 10px;">Nominal</td>
                            <td class="font-weight-bold text-dark font-monospace text-nowrap" style="padding-top: 10px; padding-bottom: 10px;">Rp {{ number_format($spj->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted pl-3" style="padding-top: 10px; padding-bottom: 10px;">Tipe / No</td>
                            <td style="padding-top: 10px; padding-bottom: 10px;">{{ trim($spj->filter_tipe . ' ' . $spj->filter_no) ?: '-' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted pl-3" style="padding-top: 10px; padding-bottom: 10px;">Rekening</td>
                            <td style="padding-top: 10px; padding-bottom: 10px; font-size: 0.88rem;">{{ $spj->rekening->kode_rekening }} - {{ $spj->rekening->nama_rekening }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted pl-3" style="padding-top: 10px; padding-bottom: 10px;">Tanggal Diajukan</td>
                            <td class="text-muted" style="padding-top: 10px; padding-bottom: 10px; font-size: 0.88rem;">
                                {{ $spj->submitted_at ? $spj->submitted_at->format('d/m/Y H:i') : $spj->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Card Tindakan --}}
            @if($spj->status_level == $targetLevel && !$spj->is_rejected)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-gavel text-warning mr-2"></i>Tindakan</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Periksa seluruh dokumen di sebelah kanan. Jika semua lengkap dan benar, klik <strong>Setujui</strong>. Jika ada yang perlu diperbaiki, isi komentar pada dokumen yang bermasalah lalu klik <strong>Tolak (Revisi)</strong>.
                    </p>
                    <div class="d-grid gap-2">
                        <form action="{{ route('approval.spj.approve', $spj) }}" method="POST"
                              data-confirm-html='<p>SPJ <strong>&quot;{{ Str::limit($spj->deskripsi, 80) }}&quot;</strong> akan disetujui dan diteruskan ke tahap berikutnya.</p><div class="alert alert-info text-left mb-0 mt-2" style="font-size:0.88rem;"><i class="fas fa-info-circle mr-1"></i> Pastikan Anda sudah memeriksa seluruh dokumen pendukung.</div>'
                              data-confirm-title="Setujui SPJ ini?"
                              data-confirm-type="success">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">
                                <i class="fas fa-check-circle mr-2"></i>Setujui SPJ
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-block font-weight-bold shadow-sm mt-2"
                                data-confirm-html='<p>SPJ <strong>&quot;{{ Str::limit($spj->deskripsi, 80) }}&quot;</strong> akan dikembalikan ke Operator untuk diperbaiki.</p><div class="alert alert-warning text-left mb-0 mt-2" style="font-size:0.88rem;"><i class="fas fa-exclamation-triangle mr-1"></i> Pastikan Anda sudah mengisi komentar revisi pada dokumen yang bermasalah.</div>'
                                data-confirm-title="Tolak SPJ ini?"
                                data-confirm-type="reject"
                                data-confirm-submit="#reject-form">
                            <i class="fas fa-times-circle mr-2"></i>Tolak SPJ (Revisi)
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- ================================ --}}
        {{-- KOLOM KANAN: Evaluasi Dokumen    --}}
        {{-- ================================ --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-folder-open text-primary mr-2"></i>Evaluasi Dokumen Pendukung</h5>
                    <small class="text-muted"><span class="text-danger font-weight-bold">*</span> = Wajib diunggah</small>
                </div>
                <div class="card-body p-3">
                    <form id="reject-form" action="{{ route('approval.spj.reject', $spj) }}" method="POST">
                        @csrf

                        <div class="d-flex flex-column" style="gap: 12px;">
                            @foreach($spj->jenisSpj->dokumenPendukungs as $dp)
                                @php
                                    $uploadedDokumen = $spj->dokumens->firstWhere('dokumen_pendukung_id', $dp->id);
                                @endphp

                                <div class="border rounded p-3 bg-white shadow-sm
                                    {{ $uploadedDokumen ? 'border-success' : ($dp->is_wajib ? 'border-danger' : 'border-light') }}"
                                    style="border-width: 1.5px !important;">

                                    <div class="row align-items-start">
                                        {{-- Nama Dokumen --}}
                                        <div class="col-md-4 mb-2 mb-md-0">
                                            <p class="mb-1 font-weight-bold text-dark" style="line-height: 1.4; font-size: 0.92rem;">
                                                {{ $dp->nama_dokumen }}
                                                @if($dp->is_wajib)
                                                    <span class="text-danger ml-1" title="Wajib">*</span>
                                                @endif
                                            </p>
                                            @if(!$dp->is_wajib)
                                                <span class="badge badge-pill" style="background-color: #e9ecef; color: #6c757d; font-size: 0.7rem;">Opsional</span>
                                            @endif
                                        </div>

                                        {{-- Status File --}}
                                        <div class="col-md-3 mb-2 mb-md-0 d-flex flex-column align-items-start justify-content-center">
                                            @if($uploadedDokumen)
                                                <span class="badge badge-pill badge-success mb-2" style="font-size: 0.8rem; padding: 5px 10px;">
                                                    <i class="fas fa-check-circle mr-1"></i>Sudah Diunggah
                                                </span>
                                                <a href="{{ asset('storage/' . $uploadedDokumen->file_path) }}" target="_blank"
                                                   class="btn btn-sm btn-outline-info font-weight-bold" style="font-size: 0.78rem;">
                                                    <i class="fas fa-external-link-alt mr-1"></i>Buka File
                                                </a>
                                            @else
                                                @if($dp->is_wajib)
                                                    <span class="badge badge-pill badge-danger" style="font-size: 0.8rem; padding: 5px 10px;">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>Belum Diunggah
                                                    </span>
                                                    <small class="text-danger mt-1" style="font-size: 0.75rem;"><i class="fas fa-exclamation-triangle mr-1"></i>Dokumen wajib!</small>
                                                @else
                                                    <span class="badge badge-pill badge-secondary" style="font-size: 0.8rem; padding: 5px 10px;">
                                                        <i class="fas fa-minus-circle mr-1"></i>Tidak Diunggah
                                                    </span>
                                                    <small class="text-muted mt-1" style="font-size: 0.75rem;">Boleh kosong</small>
                                                @endif
                                            @endif
                                        </div>

                                        {{-- Komentar Revisi --}}
                                        <div class="col-md-5">
                                            @if($uploadedDokumen)
                                                <label class="text-muted mb-1" style="font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    <i class="fas fa-comment-dots mr-1"></i>Komentar Revisi (jika ada masalah)
                                                </label>
                                                <textarea name="komentar[{{ $uploadedDokumen->id }}]"
                                                          class="form-control form-control-sm"
                                                          rows="2"
                                                          placeholder="Kosongkan jika dokumen ini sudah benar..."
                                                          style="font-size: 0.85rem; resize: vertical; border-color: #ced4da;"></textarea>
                                            @elseif($dp->is_wajib)
                                                <div class="alert alert-danger mb-0 py-2 px-3" style="font-size: 0.82rem;">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Dokumen wajib ini belum diunggah. Anda dapat menolak SPJ karena alasan kelengkapan dokumen.
                                                </div>
                                            @else
                                                <div class="text-center text-muted" style="font-size: 0.82rem; padding: 10px 0;">
                                                    <i class="fas fa-info-circle mr-1"></i>Dokumen opsional, tidak perlu komentar.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
