<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen SPJ - Dinas Komunikasi dan Informatika Kabupaten Kebumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .gov-header {
            background: linear-gradient(135deg, #1a365d 0%, #2a4a7f 100%);
            color: #fff;
            padding: 24px 0;
            text-align: center;
            border-bottom: 4px solid #c6a84b;
        }
        .gov-header .instansi {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.85;
            margin-bottom: 4px;
        }
        .gov-header .dinas {
            font-size: 1.3rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .gov-header .alamat {
            font-size: 0.75rem;
            opacity: 0.7;
            margin-top: 6px;
        }
        .verify-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .status-banner {
            padding: 20px;
            text-align: center;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 600;
        }
        .status-banner.valid {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }
        .status-banner.proses {
            background: linear-gradient(135deg, #d69e2e 0%, #b7791f 100%);
        }
        .status-banner.ditolak {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        }
        .status-banner i {
            font-size: 1.6rem;
            vertical-align: middle;
            margin-right: 8px;
        }
        .info-table th {
            color: #4a5568;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 10px 16px;
            white-space: nowrap;
            width: 35%;
            background-color: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-table td {
            padding: 10px 16px;
            font-size: 0.92rem;
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
        }
        .doc-item {
            padding: 14px 20px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .doc-item:last-child {
            border-bottom: none;
        }
        .doc-item .doc-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2d3748;
        }
        .doc-item .doc-date {
            font-size: 0.78rem;
            color: #a0aec0;
            margin-top: 2px;
        }
        .section-header {
            background-color: #f7fafc;
            padding: 14px 20px;
            font-weight: 700;
            font-size: 0.95rem;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
        }
        .gov-footer {
            text-align: center;
            padding: 20px;
            font-size: 0.78rem;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="gov-header">
        <div class="instansi">Pemerintah Daerah Kabupaten Kebumen</div>
        <div class="dinas">Dinas Komunikasi dan Informatika</div>
        <div class="alamat">Jl. K.H. Hasyim Asy'ari No.6, Panjer, Kec. Kebumen, Kabupaten Kebumen, Jawa Tengah 54312</div>
    </div>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="text-center mb-4">
                    <h5 class="fw-bold text-dark">Verifikasi Dokumen SPJ Elektronik</h5>
                    <p class="text-muted small mb-0">Halaman ini menampilkan hasil verifikasi keaslian dokumen Surat Pertanggungjawaban (SPJ).</p>
                </div>

                <div class="verify-card mb-4">
                    @if($spj->is_rejected)
                        <div class="status-banner ditolak">
                            <i class="bi bi-x-circle-fill"></i> Dokumen Ditolak / Dalam Revisi
                        </div>
                    @elseif($spj->status_level == 5)
                        <div class="status-banner valid">
                            <i class="bi bi-patch-check-fill"></i> Dokumen SPJ Sah dan Terverifikasi
                        </div>
                    @else
                        <div class="status-banner proses">
                            <i class="bi bi-hourglass-split"></i> Dokumen Dalam Proses Persetujuan
                        </div>
                    @endif

                    @php
                        $bulanIndonesia = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        $tanggal = $spj->submitted_at ?? $spj->created_at;
                        $tanggalFormatted = $tanggal->format('d') . ' ' . $bulanIndonesia[(int)$tanggal->format('m')] . ' ' . $tanggal->format('Y');

                        $statusLabel = 'Dalam Proses';
                        $statusClass = 'bg-warning text-dark';
                        if ($spj->is_rejected) {
                            $statusLabel = 'Ditolak / Revisi';
                            $statusClass = 'bg-danger';
                        } elseif ($spj->status_level == 5) {
                            $statusLabel = 'Terverifikasi';
                            $statusClass = 'bg-success';
                        } elseif ($spj->status_level == 4) {
                            $statusLabel = 'Menunggu Verifikasi Bendahara';
                            $statusClass = 'bg-info';
                        } elseif ($spj->status_level == 3) {
                            $statusLabel = 'Disetujui Sekretaris Dinas';
                            $statusClass = 'bg-primary';
                        } elseif ($spj->status_level == 2) {
                            $statusLabel = 'Disetujui Sekretaris Dinas';
                            $statusClass = 'bg-primary';
                        } elseif ($spj->status_level == 1) {
                            $statusLabel = 'Menunggu Persetujuan Kabid';
                            $statusClass = 'bg-secondary';
                        }
                    @endphp

                    <table class="info-table table table-borderless mb-0">
                        <tr>
                            <th>Pengaju</th>
                            <td>{{ $spj->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Jenis SPJ</th>
                            <td>{{ $spj->jenisSpj->nama_jenis }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $spj->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th>Nominal</th>
                            <td class="fw-semibold">Rp {{ number_format($spj->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Tipe / No</th>
                            <td>{{ $spj->filter_tipe }}{{ $spj->filter_no ? ' / ' . $spj->filter_no : '' }}</td>
                        </tr>
                        <tr>
                            <th>Status Persetujuan</th>
                            <td><span class="badge {{ $statusClass }} rounded-pill" style="font-size: 0.82rem; padding: 5px 14px;">{{ $statusLabel }}</span></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td>{{ $tanggalFormatted }}</td>
                        </tr>
                    </table>
                </div>

                @if($spj->dokumens->count() > 0)
                <div class="verify-card mb-4">
                    <div class="section-header">
                        <i class="bi bi-file-earmark-text me-2"></i>Dokumen Pendukung
                    </div>
                    @foreach($spj->dokumens as $dokumen)
                        @php
                            $tglUpload = $dokumen->created_at;
                            $tglUploadFormatted = $tglUpload->format('d') . ' ' . $bulanIndonesia[(int)$tglUpload->format('m')] . ' ' . $tglUpload->format('Y');
                        @endphp
                        <div class="doc-item">
                            <div>
                                <div class="doc-name">{{ $dokumen->dokumenPendukung->nama_dokumen }}</div>
                                <div class="doc-date">Diunggah: {{ $tglUploadFormatted }}</div>
                            </div>
                            <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i>Lihat
                            </a>
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="gov-footer">
                    <p class="mb-1">Dokumen ini dihasilkan secara elektronik oleh Sistem SPJ Elektronik.</p>
                    <p class="mb-0">© {{ date('Y') }} Dinas Komunikasi dan Informatika Kabupaten Kebumen</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
