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

            @if($spj->status_level == 3)
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 60%;" class="text-left align-middle">Nama Dokumen</th>
                                    <th style="width: 40%;" class="text-center align-middle">File / Status</th>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
