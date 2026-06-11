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
        <div class="alert alert-success alert-dismissible fade show auto-close" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show auto-close" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        </div>
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
                    <div class="list-group list-group-flush">
                        @foreach($spj->jenisSpj->dokumenPendukungs as $dp)
                            @php
                                $uploadedDokumen = $spj->dokumens->firstWhere('dokumen_pendukung_id', $dp->id);
                            @endphp
                            <div class="list-group-item px-0 py-3">
                                <div class="row align-items-center">
                                    <!-- Bagian Kiri: Nama Dokumen & Status -->
                                    <div class="col-lg-5 mb-2 mb-lg-0">
                                        <h6 class="mb-1 fw-bold">{{ $dp->nama_dokumen }}</h6>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            @if($uploadedDokumen)
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Sudah Diunggah</span>
                                                <a href="{{ asset('storage/' . $uploadedDokumen->file_path) }}" target="_blank" class="btn btn-xs btn-outline-info p-1" style="font-size: 11px;"><i class="fas fa-eye me-1"></i>Lihat</a>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i>Belum Diunggah</span>
                                            @endif
                                        </div>
                                        
                                        @if($uploadedDokumen && $uploadedDokumen->komentar_revisi)
                                            <div class="alert alert-danger mt-2 mb-0 p-2" style="font-size: 0.85rem">
                                                <strong>Komentar Revisi:</strong> {{ $uploadedDokumen->komentar_revisi }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Bagian Kanan: Form Upload & Aksi -->
                                    <div class="col-lg-7">
                                        @if($spj->status_level == 0 || $spj->is_rejected)
                                            <form action="{{ route('operator.spj.dokumen.store', $spj) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="dokumen_pendukung_id" value="{{ $dp->id }}">
                                                
                                                <div class="flex-grow-1 position-relative rounded p-2 text-center shadow-sm" style="border: 1.5px dashed #adb5bd; background-color: #f8f9fa; cursor: pointer; transition: all 0.2s ease;" onclick="document.getElementById('file_{{ $dp->id }}').click()" onmouseover="this.style.borderColor='#0d6efd'; this.style.backgroundColor='#e9ecef';" onmouseout="this.style.borderColor='#adb5bd'; this.style.backgroundColor='#f8f9fa';">
                                                    <input type="file" name="file" id="file_{{ $dp->id }}" style="display: none;" required onchange="
                                                        let fileName = this.files.length > 0 ? this.files[0].name : 'Klik untuk Pilih File';
                                                        let el = document.getElementById('fileName_{{ $dp->id }}');
                                                        el.innerText = fileName;
                                                        if(this.files.length > 0) {
                                                            el.classList.remove('text-muted');
                                                            el.classList.add('text-primary', 'fw-bold');
                                                        } else {
                                                            el.classList.add('text-muted');
                                                            el.classList.remove('text-primary', 'fw-bold');
                                                        }
                                                    ">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <i class="fas fa-cloud-upload-alt text-secondary"></i>
                                                        <span id="fileName_{{ $dp->id }}" class="text-muted small text-truncate" style="max-width: 160px; display: inline-block; vertical-align: bottom;">Klik untuk Pilih File</span>
                                                    </div>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-sm btn-primary shadow-sm" title="Unggah Dokumen">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                                
                                                @if($uploadedDokumen)
                                                    <button type="button" onclick="if(confirm('Hapus dokumen ini?')) document.getElementById('form-delete-{{ $dp->id }}').submit();" class="btn btn-sm btn-danger shadow-sm" title="Hapus Dokumen">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </form>
                                            
                                            @if($uploadedDokumen)
                                            <form id="form-delete-{{ $dp->id }}" action="{{ route('operator.spj.dokumen.destroy', [$spj, $uploadedDokumen]) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        @else
                                            <div class="text-muted small text-lg-end text-start mt-2 mt-lg-0">
                                                <i>Tidak dapat mengubah dokumen pada status ini.</i>
                                            </div>
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

@section('js')
<script>
    // Script untuk menghilangkan notifikasi secara otomatis setelah 4 detik
    setTimeout(function() {
        $('.auto-close').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 4000);
</script>
@stop
