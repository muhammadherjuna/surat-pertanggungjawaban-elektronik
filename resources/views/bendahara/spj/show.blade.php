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

            @if($spj->status_level == 3)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tindakan Bendahara</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Sebagai Bendahara, Anda adalah tahap akhir validasi. Jika sudah diverifikasi dan dicairkan, klik Verifikasi Final.</p>
                    
                    <form action="{{ route('bendahara.spj.verify', $spj) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin melakukan verifikasi final pada SPJ ini? Tindakan ini tidak bisa dibatalkan.');">
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Dokumen</th>
                                    <th>File</th>
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
