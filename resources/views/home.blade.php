@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="text-muted">Selamat Datang, <strong>{{ Auth::user()->name }}</strong>! Anda login sebagai <strong>{{ Auth::user()->role->name ?? 'User' }}</strong>.</h5>
        </div>
    </div>

    <div class="row">
        <!-- Kartu 1: Perlu Tindakan (Draft & Revisi) -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow">
                <div class="inner">
                    <h3>{{ $stats['perlu_tindakan'] }}</h3>
                    <p>Perlu Tindakan (Draft/Revisi)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('operator.spj.index') }}?status=draft" class="small-box-footer">
                    Lihat Dokumen <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Kartu 2: Menunggu Verifikasi -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow">
                <div class="inner">
                    <h3>{{ $stats['menunggu_verifikasi'] }}</h3>
                    <p>Menunggu Verifikasi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <a href="{{ route('operator.spj.index') }}?status=proses" class="small-box-footer">
                    Lihat Dokumen <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Kartu 3: Selesai -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow">
                <div class="inner">
                    <h3>{{ $stats['selesai'] }}</h3>
                    <p>SPJ Selesai (Disetujui)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('operator.spj.index') }}?status=selesai" class="small-box-footer">
                    Lihat Dokumen <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Kartu 4: Total Nilai Disetujui -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple shadow">
                <div class="inner">
                    <h3 style="font-size: 1.5rem">Rp {{ number_format($stats['total_nominal_selesai'], 0, ',', '.') }}</h3>
                    <p>Total Nilai Disetujui</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="{{ route('operator.spj.index') }}?status=selesai" class="small-box-footer">
                    Lihat Dokumen <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@stop
