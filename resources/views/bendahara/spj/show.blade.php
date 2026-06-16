@extends('adminlte::page')

@section('title', 'Detail Verifikasi SPJ')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Verifikasi SPJ</h1>
        <a href="{{ route('bendahara.spj.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
@stop

@section('content')
<div class="container-fluid">
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

            @if($spj->status_level == 4)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tindakan Bendahara</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Sebagai Bendahara, Anda adalah tahap akhir validasi. Jika sudah diverifikasi dan dicairkan, klik Verifikasi Final.</p>
                    
                    <form action="{{ route('bendahara.spj.verify', $spj) }}" method="POST" class="d-inline"
                          data-confirm-html='<p>SPJ <strong>&quot;{{ Str::limit($spj->deskripsi, 80) }}&quot;</strong> akan ditandai sebagai <strong>Terverifikasi</strong>.</p><div class="alert alert-danger text-left mb-0 mt-2" style="font-size:0.88rem;"><i class="fas fa-exclamation-triangle mr-1"></i> <strong>Perhatian:</strong> Tindakan ini bersifat final dan tidak dapat dibatalkan.</div>'
                          data-confirm-title="Verifikasi Final SPJ ini?"
                          data-confirm-type="verify">
                        @csrf
                        <button type="submit" class="btn btn-success me-2">Verifikasi Final SPJ</button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dokumen Pendukung</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column" style="gap: 15px;">
                        @foreach($spj->jenisSpj->dokumenPendukungs as $dp)
                            @php
                                $uploadedDokumen = $spj->dokumens->firstWhere('dokumen_pendukung_id', $dp->id);
                            @endphp

                            <div class="border rounded p-3 bg-white shadow-sm
                                {{ $uploadedDokumen ? 'border-success' : ($dp->is_wajib ? 'border-danger' : 'border-light') }}"
                                style="border-width: 1.5px !important;">

                                <div class="row align-items-center">

                                    <div class="col-md-5 mb-2 mb-md-0">
                                        <p class="mb-1 font-weight-bold text-dark" style="line-height: 1.4; font-size: 0.95rem;">
                                            {{ $dp->nama_dokumen }}
                                            @if($dp->is_wajib)
                                                <span class="text-danger ml-1" title="Wajib">*</span>
                                            @endif
                                        </p>
                                        @if(!$dp->is_wajib)
                                            <span class="badge badge-pill" style="background-color: #e9ecef; color: #6c757d; font-size: 0.75rem;">Opsional</span>
                                        @endif
                                    </div>


                                    <div class="col-md-7 mb-2 mb-md-0 d-flex align-items-center justify-content-md-end justify-content-start" style="gap: 10px;">
                                        @if($uploadedDokumen)
                                            <span class="badge badge-pill badge-success" style="font-size: 0.85rem; padding: 6px 12px;">
                                                <i class="fas fa-check-circle mr-1"></i>Sudah Diunggah
                                            </span>
                                            <a href="{{ asset('storage/' . $uploadedDokumen->file_path) }}" target="_blank"
                                               class="btn btn-sm btn-info text-white font-weight-bold shadow-sm" style="font-size: 0.85rem;">
                                                <i class="fas fa-external-link-alt mr-1"></i>Buka File
                                            </a>
                                        @else
                                            @if($dp->is_wajib)
                                                <span class="badge badge-pill badge-danger" style="font-size: 0.85rem; padding: 6px 12px;">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>Belum Diunggah
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-secondary" style="font-size: 0.85rem; padding: 6px 12px;">
                                                    <i class="fas fa-minus-circle mr-1"></i>Tidak Diunggah
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
