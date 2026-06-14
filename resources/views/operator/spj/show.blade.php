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
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show auto-close" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title m-0 mt-1">Informasi SPJ</h5>
                    <div class="card-tools d-flex" style="gap: 8px;">
                        @if($spj->status_level == 0 || $spj->is_rejected)
                            <a href="{{ route('operator.spj.edit', $spj) }}" class="btn btn-sm btn-warning shadow-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#submitModal-{{ $spj->id }}">
                                <i class="fas fa-paper-plane mr-1"></i> Ajukan
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th style="width: 30%" class="text-left">Deskripsi</th>
                            <td style="width: 1%">:</td>
                            <td class="text-left">{{ $spj->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th>Jenis SPJ</th>
                            <td>:</td>
                            <td>{{ $spj->jenisSpj->nama_jenis }}</td>
                        </tr>
                        <tr>
                            <th>Nominal</th>
                            <td>:</td>
                            <td class="font-monospace">Rp {{ number_format($spj->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Tipe / No</th>
                            <td>:</td>
                            <td>{{ trim($spj->filter_tipe . ' ' . $spj->filter_no) }}</td>
                        </tr>
                        <tr>
                            <th>Rekening</th>
                            <td>:</td>
                            <td>{{ $spj->rekening->kode_rekening }} - {{ $spj->rekening->nama_rekening }}</td>
                        </tr>
                        <tr>
                            <th class="align-middle">Status</th>
                            <td class="align-middle">:</td>
                            <td class="align-middle"> 
                                @if($spj->is_rejected)
                                    <span class="badge bg-danger"><i class="fas fa-redo mr-2"></i>Perlu Revisi</span>
                                @elseif($spj->status_level == 0)
                                    <span class="badge bg-secondary"><i class="fas fa-file-alt mr-2"></i>Draft</span>
                                @elseif($spj->status_level == 1)
                                    <span class="badge bg-info text-dark"><i class="fas fa-clock mr-2"></i>Menunggu Kabid</span>
                                @elseif($spj->status_level == 2)
                                    <span class="badge bg-primary"><i class="fas fa-check mr-2"></i>Disetujui Kabid</span>
                                @elseif($spj->status_level == 3)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-check-double mr-2"></i>Disetujui Sekdin</span>
                                @elseif($spj->status_level == 4)
                                    <span class="badge bg-success"><i class="fas fa-check-circle mr-2"></i>Terverifikasi</span>
                                @endif
                            </td>
                        </tr>
                    </table>
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
                            <div class="list-group-item px-0 py-3" id="doc-row-{{ $dp->id }}">
                                <div class="row align-items-center">
                                    <!-- Bagian Kiri: Nama Dokumen & Status -->
                                    <div class="col-lg-5 mb-2 mb-lg-0">
                                        <h6 class="mb-1 fw-bold">
                                            {{ $dp->nama_dokumen }}
                                            @if($dp->is_wajib)
                                                <span class="text-danger" title="Wajib Diunggah" data-toggle="tooltip">*</span>
                                            @endif
                                        </h6>
                                        <div class="mt-1">
                                            @if($uploadedDokumen)
                                                <span class="badge bg-success"><i class="fas fa-check-circle mr-2"></i>Sudah Diunggah</span>
                                            @else
                                                @if($dp->is_wajib)
                                                    <span class="badge bg-danger"><i class="fas fa-exclamation-circle mr-2"></i>Belum Diunggah (Wajib)</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="fas fa-clock mr-2"></i>Belum Diunggah (Opsional)</span>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        @if($uploadedDokumen && $uploadedDokumen->komentar_revisi)
                                            <div class="alert alert-danger mt-2 mb-0 p-2" style="font-size: 0.85rem">
                                                <strong>Komentar Revisi:</strong> {{ $uploadedDokumen->komentar_revisi }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Bagian Kanan: Aksi / Form Upload -->
                                    <div class="col-lg-7">
                                        @if($uploadedDokumen)
                                            {{-- UI Jika Dokumen SUDAH Diunggah --}}
                                            <div class="d-flex justify-content-lg-end justify-content-start flex-wrap" style="gap: 8px;">
                                                <!-- Tombol Lihat -->
                                                <a href="{{ asset('storage/' . $uploadedDokumen->file_path) }}" target="_blank" class="btn btn-sm btn-info text-white shadow-sm" title="Lihat Dokumen">
                                                    <i class="fas fa-eye mr-2"></i> Lihat
                                                </a>

                                                @if($spj->status_level == 0 || $spj->is_rejected)
                                                    <!-- Tombol Ubah (Memicu File Input tersembunyi) -->
                                                    <form action="{{ route('operator.spj.dokumen.store', $spj) }}" method="POST" enctype="multipart/form-data" class="m-0 p-0 ajax-form" id="form-update-{{ $dp->id }}" data-row-id="doc-row-{{ $dp->id }}">
                                                        @csrf
                                                        <input type="hidden" name="dokumen_pendukung_id" value="{{ $dp->id }}">
                                                        <input type="file" name="file" id="file_update_{{ $dp->id }}" style="display: none;" required onchange="this.form.requestSubmit();">
                                                        <button type="button" class="btn btn-sm btn-warning shadow-sm" onclick="document.getElementById('file_update_{{ $dp->id }}').click();" title="Ubah Dokumen">
                                                            <i class="fas fa-edit mr-2"></i> Ubah
                                                        </button>
                                                    </form>

                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('operator.spj.dokumen.destroy', [$spj, $uploadedDokumen]) }}" method="POST" class="m-0 p-0 ajax-form" data-row-id="doc-row-{{ $dp->id }}" onsubmit="if(!confirm('Yakin ingin menghapus dokumen ini?')) { event.preventDefault(); return false; }">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus Dokumen">
                                                            <i class="fas fa-trash mr-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="text-muted small align-self-center ms-2">
                                                        <i>(Terkunci)</i>
                                                    </div>
                                                @endif
                                            </div>

                                        @else
                                            {{-- UI Jika Dokumen BELUM Diunggah --}}
                                            @if($spj->status_level == 0 || $spj->is_rejected)
                                                <form action="{{ route('operator.spj.dokumen.store', $spj) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center ajax-form" style="gap: 8px;" data-row-id="doc-row-{{ $dp->id }}">
                                                    @csrf
                                                    <input type="hidden" name="dokumen_pendukung_id" value="{{ $dp->id }}">
                                                    
                                                    <!-- Area Dropzone Mini -->
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
                                                        <div class="d-flex align-items-center justify-content-center" style="gap: 8px;">
                                                            <i class="fas fa-cloud-upload-alt text-secondary"></i>
                                                            <span id="fileName_{{ $dp->id }}" class="text-muted small text-truncate" style="max-width: 160px; display: inline-block; vertical-align: bottom;">Klik untuk Pilih File</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Tombol Unggah -->
                                                    <button type="submit" class="btn btn-sm btn-primary shadow-sm" title="Unggah Dokumen">
                                                        <i class="fas fa-upload mr-2"></i> Unggah
                                                    </button>
                                                </form>
                                            @else
                                                <div class="text-muted small text-lg-end text-start mt-2 mt-lg-0">
                                                    <i>Tidak dapat mengunggah dokumen pada status ini.</i>
                                                </div>
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

    <!-- Modal Konfirmasi Ajukan -->
    @if($spj->status_level == 0 || $spj->is_rejected)
        <div class="modal fade" id="submitModal-{{ $spj->id }}" tabindex="-1" role="dialog" aria-labelledby="submitModalLabel-{{ $spj->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title font-weight-bold" id="submitModalLabel-{{ $spj->id }}">
                            <i class="fas fa-paper-plane mr-2"></i> Konfirmasi Pengajuan
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left text-dark">
                        <p>Apakah Anda yakin ingin mengajukan SPJ <strong>"{{ $spj->deskripsi }}"</strong> untuk tahapan persetujuan?</p>
                        <div class="alert alert-warning mt-3 mb-0" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Perhatian:</strong> Pastikan semua dokumen bukti pendukung wajib sudah diunggah dengan lengkap sebelum mengajukan.
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <form action="{{ route('operator.spj.submit', $spj) }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="btn btn-success font-weight-bold">
                                <i class="fas fa-check mr-1"></i> Ya, Ajukan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@stop

@section('js')
<script>
    // Script untuk menghilangkan notifikasi global secara otomatis
    setTimeout(function() {
        $('.auto-close').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 4000);

    // AJAX Form Submission untuk mencegah reload halaman
    document.addEventListener('submit', async function(e) {
        if (e.target && e.target.classList.contains('ajax-form')) {
            if (e.defaultPrevented) return; // Stop if confirmation cancelled
            e.preventDefault();
            
            let form = e.target;
            let rowId = form.dataset.rowId;
            let btn = form.querySelector('button[type="submit"]');
            
            let originalContent = '';
            if (btn) {
                originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Proses...';
                btn.disabled = true;
            }

            try {
                let formData = new FormData(form);
                
                let response = await fetch(form.action, {
                    method: form.method || 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    let html = await response.text();
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    
                    let newRow = doc.getElementById(rowId);
                    if (newRow) {
                        // Replace only the specific row
                        document.getElementById(rowId).innerHTML = newRow.innerHTML;
                    }
                    
                    // Optional: show a mini toast/alert manually here
                    // alert('Berhasil!');
                } else {
                    alert('Gagal memproses dokumen. Silakan coba lagi.');
                    if (btn) {
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    }
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
                if (btn) {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            }
        }
    });
</script>
@stop
