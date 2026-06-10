<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validasi SPJ - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); }
        .status-badge { font-size: 1rem; padding: 0.5rem 1rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Sistem Verifikasi SPJ Elektronik</h2>
                    <p class="text-muted">Dinas Perpustakaan dan Kearsipan Kota Cilegon</p>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h4 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i> Dokumen SPJ Valid</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%" class="text-muted">Dibuat Oleh</th>
                                <td>{{ $spj->user->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Jenis SPJ</th>
                                <td>{{ $spj->jenisSpj->nama_jenis }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Deskripsi</th>
                                <td>{{ $spj->deskripsi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Nominal</th>
                                <td>Rp {{ number_format($spj->nominal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tipe & No</th>
                                <td>{{ $spj->filter_tipe }} {{ $spj->filter_no ? '- ' . $spj->filter_no : '' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status Persetujuan</th>
                                <td>
                                    @if($spj->is_rejected)
                                        <span class="badge bg-danger status-badge">Ditolak / Revisi</span>
                                    @elseif($spj->status_level == 4)
                                        <span class="badge bg-success status-badge">Selesai (Dicairkan)</span>
                                    @else
                                        <span class="badge bg-warning text-dark status-badge">Sedang Diproses (Tahap {{ $spj->status_level }})</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Validasi Akhir</th>
                                <td>{{ $spj->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light fw-bold">
                        <i class="bi bi-file-earmark-text me-2"></i> Dokumen Pendukung
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($spj->dokumens as $dokumen)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $dokumen->dokumenPendukung->nama_dokumen }}</h6>
                                        <small class="text-muted">Diunggah: {{ $dokumen->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-4">
                                    Tidak ada dokumen pendukung.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
