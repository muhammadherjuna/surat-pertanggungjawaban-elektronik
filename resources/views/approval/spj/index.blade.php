@extends('adminlte::page')

@section('title', 'Daftar SPJ Butuh Persetujuan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar SPJ Butuh Persetujuan</h1>
    </div>
@stop

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show auto-close" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show auto-close" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-primary card-outline card-outline-tabs shadow-sm">
        <div class="card-header p-0 border-bottom-0 bg-white">
            <ul class="nav nav-tabs" id="spjApprovalTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold py-3 px-4" id="pending-tab" data-toggle="pill" href="#pending" role="tab" aria-controls="pending" aria-selected="true">
                        <i class="fas fa-inbox mr-2 text-primary"></i> Belum Disetujui ({{ $pendingSpjs->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold py-3 px-4" id="history-tab" data-toggle="pill" href="#history" role="tab" aria-controls="history" aria-selected="false">
                        <i class="fas fa-history mr-2 text-success"></i> Riwayat Persetujuan ({{ $historySpjs->count() }})
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="spjApprovalTabContent">
                
                <!-- Tab 1: Belum Disetujui -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 5%;" class="text-center align-middle">No</th>
                                    <th style="width: 15%;" class="text-left align-middle">Pengaju</th>
                                    <th style="width: 30%;" class="text-left align-middle">Deskripsi</th>
                                    <th style="width: 15%;" class="text-left align-middle">Jenis SPJ</th>
                                    <th style="width: 15%;" class="text-left align-middle">Nominal</th>
                                    <th style="width: 12%;" class="text-left align-middle">Tanggal Diajukan</th>
                                    <th style="width: 8%;" class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingSpjs as $index => $spj)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle text-left font-weight-bold">{{ $spj->user->name }}</td>
                                        <td class="align-middle text-left text-wrap">{{ $spj->deskripsi }}</td>
                                        <td class="align-middle text-left">{{ $spj->jenisSpj->nama_jenis }}</td>
                                        <td class="align-middle text-left font-monospace text-nowrap">
                                            Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="align-middle text-left text-nowrap text-muted">
                                            {{ $spj->submitted_at ? $spj->submitted_at->format('d/m/Y H:i') : $spj->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('approval.spj.show', $spj) }}" class="btn btn-sm btn-info btn-action" title="Detail & Evaluasi">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                                            Tidak ada data SPJ yang menunggu persetujuan Anda saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Riwayat Persetujuan -->
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 5%;" class="text-center align-middle">No</th>
                                    <th style="width: 15%;" class="text-left align-middle">Pengaju</th>
                                    <th style="width: 28%;" class="text-left align-middle">Deskripsi</th>
                                    <th style="width: 15%;" class="text-left align-middle">Jenis SPJ</th>
                                    <th style="width: 15%;" class="text-left align-middle">Nominal</th>
                                    <th style="width: 14%;" class="text-left align-middle">Posisi Dokumen</th>
                                    <th style="width: 8%;" class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historySpjs as $index => $spj)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle text-left font-weight-bold">{{ $spj->user->name }}</td>
                                        <td class="align-middle text-left text-wrap">{{ $spj->deskripsi }}</td>
                                        <td class="align-middle text-left">{{ $spj->jenisSpj->nama_jenis }}</td>
                                        <td class="align-middle text-left font-monospace text-nowrap">
                                            Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="align-middle text-left">
                                            @if($spj->status_level == 2)
                                                <span class="badge bg-warning text-dark"><i class="fas fa-clock mr-1"></i> Sedang di Sekdin</span>
                                            @elseif($spj->status_level == 3)
                                                <span class="badge bg-warning text-dark"><i class="fas fa-clock mr-1"></i> Sedang di Kadin</span>
                                            @elseif($spj->status_level == 4)
                                                <span class="badge bg-info text-dark"><i class="fas fa-clock mr-1"></i> Menunggu Bendahara</span>
                                            @elseif($spj->status_level == 5)
                                                <span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i> Selesai (Terverifikasi)</span>
                                            @else
                                                <span class="badge bg-secondary">Proses</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('approval.spj.show', $spj) }}" class="btn btn-sm btn-info btn-action" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-history fa-2x text-secondary mb-2 d-block"></i>
                                            Belum ada riwayat SPJ yang Anda setujui.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    // Auto-hide notifikasi setelah 4 detik
    setTimeout(function() {
        $('.auto-close').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 4000);
</script>
@stop
