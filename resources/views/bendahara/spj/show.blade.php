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
