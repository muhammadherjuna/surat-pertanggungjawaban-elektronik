@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="mr-3 bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Selamat Datang, {{ Auth::user()->name }}!</h4>
                        <p class="text-muted mb-0">
                            Anda masuk sebagai <span class="badge badge-primary font-weight-bold px-2 py-1">{{ Auth::user()->role->name ?? 'User' }}</span>
                            @if(Auth::user()->bidang)
                                pada Bidang <span class="badge badge-secondary font-weight-bold px-2 py-1">{{ Auth::user()->bidang->nama_bidang }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat Widgets --}}
    <div class="row">
        @if($roleLevel == 0)
            {{-- Operator Widgets --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner"><h3>{{ $stats['perlu_tindakan'] }}</h3><p>Perlu Tindakan (Draft/Revisi)</p></div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="{{ route('operator.spj.index') }}?status=draft" class="small-box-footer">Lihat di Transaksi SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner"><h3>{{ $stats['menunggu_verifikasi'] }}</h3><p>Menunggu Persetujuan</p></div>
                    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                    <a href="{{ route('operator.spj.index') }}?status=proses" class="small-box-footer">Lihat di Transaksi SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner"><h3>{{ $stats['selesai'] }}</h3><p>SPJ Selesai (Terverifikasi)</p></div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                    <a href="{{ route('operator.spj.index') }}?status=selesai" class="small-box-footer">Lihat di Transaksi SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple shadow-sm">
                    <div class="inner"><h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3><p>Total Nilai SPJ Selesai</p></div>
                    <div class="icon"><i class="fas fa-wallet"></i></div>
                    <a href="{{ route('operator.spj.index') }}?status=selesai" class="small-box-footer">Lihat di Transaksi SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        @elseif(in_array($roleLevel, [1, 2, 3]))
            {{-- Approval Widgets --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning text-dark shadow-sm">
                    <div class="inner"><h3>{{ $stats['perlu_tindakan'] }}</h3><p>Menunggu Persetujuan Anda</p></div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer text-dark">Lihat di Persetujuan SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner"><h3>{{ $stats['menunggu_verifikasi'] }}</h3><p>Telah Anda Setujui (Proses Lanjut)</p></div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer">Lihat di Persetujuan SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner"><h3 class="text-nowrap">Rp {{ number_format($stats['selesai'], 0, ',', '.') }}</h3><p>Nominal Menunggu Persetujuan</p></div>
                    <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer">Lihat di Persetujuan SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner"><h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3><p>Nominal Telah Anda Setujui</p></div>
                    <div class="icon"><i class="fas fa-wallet"></i></div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer">Lihat di Persetujuan SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        @elseif($roleLevel == 4)
            {{-- Bendahara Widgets --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning text-dark shadow-sm">
                    <div class="inner"><h3>{{ $stats['perlu_tindakan'] }}</h3><p>Menunggu Verifikasi Anda</p></div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer text-dark">Lihat di Daftar SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner"><h3>{{ $stats['menunggu_verifikasi'] }}</h3><p>Selesai / Terverifikasi</p></div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer">Lihat di Daftar SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner"><h3 class="text-nowrap">Rp {{ number_format($stats['selesai'], 0, ',', '.') }}</h3><p>Nominal Menunggu Verifikasi</p></div>
                    <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer">Lihat di Daftar SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner"><h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3><p>Nominal Selesai Diverifikasi</p></div>
                    <div class="icon"><i class="fas fa-wallet"></i></div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer">Lihat di Daftar SPJ <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        @else
            {{-- Super Admin Widgets --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary shadow-sm">
                    <div class="inner"><h3>{{ $stats['perlu_tindakan'] }}</h3><p>Total Pengguna</p></div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <a href="{{ route('master.users.index') }}" class="small-box-footer">Kelola Pengguna <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner"><h3>{{ $stats['menunggu_verifikasi'] }}</h3><p>Total Bidang</p></div>
                    <div class="icon"><i class="fas fa-building"></i></div>
                    <a href="{{ route('master.bidangs.index') }}" class="small-box-footer">Kelola Bidang <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner"><h3>{{ $stats['selesai'] }}</h3><p>Total SPJ Terdaftar</p></div>
                    <div class="icon"><i class="fas fa-file-alt"></i></div>
                    <span class="small-box-footer d-block" style="padding: 3px 0;">&nbsp;</span>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple shadow-sm">
                    <div class="inner"><h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3><p>Total Nilai SPJ</p></div>
                    <div class="icon"><i class="fas fa-wallet"></i></div>
                    <span class="small-box-footer d-block" style="padding: 3px 0;">&nbsp;</span>
                </div>
            </div>
        @endif
    </div>

    {{-- ===================================== --}}
    {{-- LOG AKTIVITAS SPJ (Monitoring Umum) --}}
    {{-- ===================================== --}}
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-muted font-weight-bold">
                        @if($roleLevel == 0)
                            <i class="fas fa-history text-primary mr-2"></i> Log Aktivitas SPJ Saya
                            <small class="text-muted font-weight-normal ml-1">(semua status)</small>
                        @elseif(in_array($roleLevel, [1, 2, 3]))
                            <i class="fas fa-stream text-primary mr-2"></i> Log Aktivitas SPJ di Bidang Anda
                            <small class="text-muted font-weight-normal ml-1">(semua SPJ dalam jangkauan Anda)</small>
                        @elseif($roleLevel == 4)
                            <i class="fas fa-stream text-primary mr-2"></i> Log Aktivitas SPJ (Antrian & Terverifikasi)
                        @else
                            <i class="fas fa-list text-primary mr-2"></i> Log Aktivitas SPJ Sistem
                        @endif
                    </h5>
                    <div>
                        @if($roleLevel == 0)
                            <a href="{{ route('operator.spj.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                                <i class="fas fa-th-list mr-1"></i> Transaksi SPJ Lengkap
                            </a>
                        @elseif(in_array($roleLevel, [1, 2, 3]))
                            <a href="{{ route('approval.spj.index') }}" class="btn btn-sm btn-outline-warning font-weight-bold">
                                <i class="fas fa-inbox mr-1"></i> Buka Inbox Persetujuan
                            </a>
                        @elseif($roleLevel == 4)
                            <a href="{{ route('bendahara.spj.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                                <i class="fas fa-th-list mr-1"></i> Daftar SPJ Lengkap
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 5%;" class="text-center align-middle">No</th>
                                    @if($roleLevel != 0)
                                        <th style="width: 16%;" class="text-left align-middle">Pengaju</th>
                                    @endif
                                    <th class="text-left align-middle">Deskripsi</th>
                                    <th style="width: 16%;" class="text-left align-middle">Nominal</th>
                                    <th style="width: 14%;" class="text-left align-middle">Status</th>
                                    <th style="width: 13%;" class="text-left align-middle">Tanggal</th>
                                    <th style="width: 9%;" class="text-center align-middle">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestSpjs as $index => $spj)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $index + 1 }}</td>
                                        @if($roleLevel != 0)
                                            <td class="align-middle text-left font-weight-bold">{{ $spj->user->name }}</td>
                                        @endif
                                        <td class="align-middle text-left text-wrap">{{ $spj->deskripsi }}</td>
                                        <td class="align-middle text-left font-monospace text-nowrap">
                                            Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="align-middle text-left">
                                            @if($spj->is_rejected)
                                                <span class="badge bg-danger"><i class="fas fa-redo mr-1"></i>Perlu Revisi</span>
                                            @elseif($spj->status_level == 0)
                                                <span class="badge bg-secondary"><i class="fas fa-file-alt mr-1"></i>Draft</span>
                                            @elseif($spj->status_level == 1)
                                                <span class="badge bg-warning text-dark"><i class="fas fa-clock mr-1"></i>Menunggu Kabid</span>
                                            @elseif($spj->status_level == 2)
                                                <span class="badge bg-primary"><i class="fas fa-check mr-1"></i>Disetujui Kabid</span>
                                            @elseif($spj->status_level == 3)
                                                <span class="badge bg-info text-dark"><i class="fas fa-check-double mr-1"></i>Disetujui Sekdin</span>
                                            @elseif($spj->status_level == 4)
                                                <span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i>Terverifikasi</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-left text-nowrap text-muted" style="font-size: 0.85rem;">
                                            {{ $spj->submitted_at ? $spj->submitted_at->format('d/m/Y H:i') : $spj->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($roleLevel == 0)
                                                <a href="{{ route('operator.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif(in_array($roleLevel, [1, 2, 3]))
                                                <a href="{{ route('approval.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif($roleLevel == 4)
                                                @if($spj->status_level >= 3)
                                                    <a href="{{ route('bendahara.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $roleLevel != 0 ? 7 : 6 }}" class="text-center py-5 text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                            Belum ada aktivitas SPJ yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($roleLevel == 0)
                    <div class="card-footer bg-white text-right py-2">
                        <a href="{{ route('operator.spj.index') }}" class="text-primary small font-weight-bold">
                            Lihat Semua Transaksi SPJ Saya <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @elseif(in_array($roleLevel, [1, 2, 3]))
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted small"><i class="fas fa-info-circle mr-1"></i>Tabel ini menampilkan log monitoring umum. Untuk tindakan persetujuan, gunakan menu <strong>Persetujuan SPJ</strong> di sidebar.</span>
                        <a href="{{ route('approval.spj.index') }}" class="btn btn-sm btn-warning text-dark font-weight-bold">
                            <i class="fas fa-inbox mr-1"></i> Inbox Persetujuan
                        </a>
                    </div>
                @elseif($roleLevel == 4)
                    <div class="card-footer bg-white text-right py-2">
                        <a href="{{ route('bendahara.spj.index') }}" class="text-primary small font-weight-bold">
                            Lihat Semua Daftar SPJ <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
