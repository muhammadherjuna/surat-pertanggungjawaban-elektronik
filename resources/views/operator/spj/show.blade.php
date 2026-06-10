@extends('adminlte::page')

@section('title', 'Detail SPJ')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail SPJ</h1>
        <a href="{{ route('operator.spj.index') }}" class="btn btn-secondary">Kembali</a>
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
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi SPJ</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Deskripsi</th>
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
                        <tr>
                            <th>Status</th>
                            <td>: 
                                @if($spj->status_level == 0) Draft / Diajukan
                                @elseif($spj->status_level == 1) Disetujui Kabid
                                @elseif($spj->status_level == 2) Disetujui Sekdin
                                @elseif($spj->status_level == 3) Disetujui Kadin
                                @elseif($spj->status_level == 4) Terverifikasi
                                @endif
                                @if($spj->is_rejected)
                                    <span class="badge bg-danger ms-2">Revisi</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    @if($spj->status_level == 0 || $spj->is_rejected)
                        <a href="{{ route('operator.spj.edit', $spj) }}" class="btn btn-warning">Edit Informasi SPJ</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dokumen Pendukung</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Dokumen</th>
                                    <th>Status Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($spj->jenisSpj->dokumenPendukungs as $dp)
                                    @php
                                        $uploadedDokumen = $spj->dokumens->firstWhere('dokumen_pendukung_id', $dp->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $dp->nama_dokumen }}
                                            @if($uploadedDokumen && $uploadedDokumen->komentar_revisi)
                                                <div class="alert alert-danger mt-2 mb-0 p-2" style="font-size: 0.85rem">
                                                    <strong>Komentar Revisi:</strong> {{ $uploadedDokumen->komentar_revisi }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($uploadedDokumen)
                                                <span class="badge bg-success">Sudah Diunggah</span>
                                                <br>
                                                <a href="{{ asset('storage/' . $uploadedDokumen->file_path) }}" target="_blank" class="btn btn-sm btn-link mt-1 p-0">Lihat File</a>
                                            @else
                                                <span class="badge bg-secondary">Belum Diunggah</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($spj->status_level == 0 || $spj->is_rejected)
                                                <form action="{{ route('operator.spj.dokumen.store', $spj) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                                    @csrf
                                                    <input type="hidden" name="dokumen_pendukung_id" value="{{ $dp->id }}">
                                                    <input type="file" name="file" class="form-control form-control-sm me-2" required>
                                                    <button type="submit" class="btn btn-sm btn-primary">Unggah</button>
                                                </form>
                                                
                                                @if($uploadedDokumen)
                                                <form action="{{ route('operator.spj.dokumen.destroy', [$spj, $uploadedDokumen]) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus dokumen ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger w-100">Hapus Dokumen</button>
                                                </form>
                                                @endif
                                            @else
                                                -
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
