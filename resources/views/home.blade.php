@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-white border-0 shadow-sm p-3">
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

    <div class="row">
        @if($roleLevel == 0)
            <!-- Operator Widgets -->
            <!-- Widget 1: Perlu Tindakan -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['perlu_tindakan'] }}</h3>
                        <p>Perlu Tindakan (Draft/Revisi)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <a href="{{ route('operator.spj.index') }}?status=draft" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 2: Menunggu Verifikasi -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['menunggu_verifikasi'] }}</h3>
                        <p>Menunggu Persetujuan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <a href="{{ route('operator.spj.index') }}?status=proses" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 3: SPJ Selesai -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['selesai'] }}</h3>
                        <p>SPJ Selesai (Terverifikasi)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('operator.spj.index') }}?status=selesai" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 4: Total Nilai Selesai -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple shadow-sm">
                    <div class="inner">
                        <h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3>
                        <p>Total Nilai SPJ Selesai</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <a href="{{ route('operator.spj.index') }}?status=selesai" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        @elseif(in_array($roleLevel, [1, 2, 3]))
            <!-- Approval Widgets -->
            <!-- Widget 1: Menunggu Persetujuan Anda -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning text-dark shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['perlu_tindakan'] }}</h3>
                        <p>Menunggu Persetujuan Anda</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer text-dark">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 2: Telah Anda Setujui -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['menunggu_verifikasi'] }}</h3>
                        <p>Telah Anda Setujui (Proses Lanjut)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 3: Nominal Menunggu -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner">
                        <h3 class="text-nowrap">Rp {{ number_format($stats['selesai'], 0, ',', '.') }}</h3>
                        <p>Nominal Menunggu Persetujuan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 4: Nominal Disetujui -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3>
                        <p>Nominal Telah Anda Setujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <a href="{{ route('approval.spj.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        @elseif($roleLevel == 4)
            <!-- Bendahara Widgets -->
            <!-- Widget 1: Menunggu Verifikasi Anda -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning text-dark shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['perlu_tindakan'] }}</h3>
                        <p>Menunggu Verifikasi Anda</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer text-dark">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 2: Selesai / Terverifikasi -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['menunggu_verifikasi'] }}</h3>
                        <p>Selesai / Terverifikasi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 3: Nominal Menunggu Verifikasi -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner">
                        <h3 class="text-nowrap">Rp {{ number_format($stats['selesai'], 0, ',', '.') }}</h3>
                        <p>Nominal Menunggu Verifikasi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 4: Nominal Selesai Diverifikasi -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3>
                        <p>Nominal Selesai Diverifikasi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <a href="{{ route('bendahara.spj.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        @else
            <!-- Super Admin Widgets -->
            <!-- Widget 1: Total Pengguna -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['perlu_tindakan'] }}</h3>
                        <p>Total Pengguna</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('master.users.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 2: Total Bidang -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['menunggu_verifikasi'] }}</h3>
                        <p>Total Bidang</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="{{ route('master.bidangs.index') }}" class="small-box-footer">
                        Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Widget 3: Total SPJ Terdaftar -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3>{{ $stats['selesai'] }}</h3>
                        <p>Total SPJ Terdaftar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="small-box-footer" style="cursor: default; padding: 3px 0; display: block;">&nbsp;</span>
                </div>
            </div>
            <!-- Widget 4: Total Nilai SPJ -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple shadow-sm">
                    <div class="inner">
                        <h3 class="text-nowrap">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3>
                        <p>Total Nilai SPJ</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <span class="small-box-footer" style="cursor: default; padding: 3px 0; display: block;">&nbsp;</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Daftar SPJ Terbaru/Antrean Aktif -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-muted font-weight-bold">
                        @if($roleLevel == 0)
                            <i class="fas fa-history text-primary mr-2"></i> 5 SPJ Terbaru Anda
                        @elseif(in_array($roleLevel, [1, 2, 3]))
                            <i class="fas fa-hourglass-half text-primary mr-2"></i> 5 Antrean Persetujuan SPJ Terbaru
                        @elseif($roleLevel == 4)
                            <i class="fas fa-hourglass-half text-primary mr-2"></i> 5 Antrean Verifikasi SPJ Terbaru
                        @else
                            <i class="fas fa-list text-primary mr-2"></i> 5 SPJ Terbaru Sistem
                        @endif
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 5%;" class="text-center align-middle">No</th>
                                    @if($roleLevel != 0)
                                        <th style="width: 20%;" class="text-left align-middle">Pengaju</th>
                                    @endif
                                    <th class="text-left align-middle">Deskripsi</th>
                                    <th style="width: 20%;" class="text-left align-middle">Nominal</th>
                                    <th style="width: 20%;" class="text-left align-middle">Tanggal</th>
                                    <th style="width: 10%;" class="text-center align-middle">Aksi</th>
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
                                        <td class="align-middle text-left text-nowrap text-muted">
                                            {{ $spj->submitted_at ? $spj->submitted_at->format('d/m/Y H:i') : $spj->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($roleLevel == 0)
                                                <a href="{{ route('operator.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif(in_array($roleLevel, [1, 2, 3]))
                                                <a href="{{ route('approval.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail & Evaluasi">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                            @elseif($roleLevel == 4)
                                                <a href="{{ route('bendahara.spj.show', $spj) }}" class="btn btn-sm btn-info text-white shadow-sm font-weight-bold" title="Detail & Verifikasi">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $roleLevel != 0 ? 6 : 5 }}" class="text-center py-5 text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                            Tidak ada aktivitas SPJ terbaru.
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
