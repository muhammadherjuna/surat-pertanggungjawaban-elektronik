@extends('adminlte::page')

@section('title', 'Daftar SPJ Saya')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar SPJ Saya</h1>
        <a href="{{ route('operator.spj.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Buat SPJ Baru
        </a>
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

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 4%;" class="text-center">#</th>
                            <th style="width: 22%;">Deskripsi</th>
                            <th style="width: 14%;">Jenis SPJ</th>
                            <th style="width: 13%;" class="text-end">Nominal</th>
                            <th style="width: 13%;" class="text-center">Tipe / No</th>
                            <th style="width: 15%;" class="text-center">Status</th>
                            <th style="width: 19%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $index => $spj)
                            <tr>
                                {{-- Nomor Urut --}}
                                <td class="text-center text-muted">{{ $index + 1 }}</td>

                                {{-- Deskripsi --}}
                                <td>
                                    <span class="fw-semibold">{{ $spj->deskripsi }}</span>
                                </td>

                                {{-- Jenis SPJ --}}
                                <td class="small">{{ $spj->jenisSpj->nama_jenis }}</td>

                                {{-- Nominal: rata kanan --}}
                                <td class="text-end font-monospace">
                                    Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                </td>

                                {{-- Tipe / No --}}
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $spj->filter_tipe }}</span>
                                    @if($spj->filter_no)
                                        <span class="text-muted small">/ {{ $spj->filter_no }}</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="text-center">
                                    @if($spj->is_rejected)
                                        <span class="badge bg-danger"><i class="fas fa-redo me-1"></i>Perlu Revisi</span>
                                    @elseif($spj->status_level == 0)
                                        <span class="badge bg-secondary"><i class="fas fa-file-alt me-1"></i>Draft</span>
                                    @elseif($spj->status_level == 1)
                                        <span class="badge bg-info text-dark"><i class="fas fa-clock me-1"></i>Menunggu Kabid</span>
                                    @elseif($spj->status_level == 2)
                                        <span class="badge bg-primary"><i class="fas fa-check me-1"></i>Disetujui Kabid</span>
                                    @elseif($spj->status_level == 3)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-check-double me-1"></i>Disetujui Sekdin</span>
                                    @elseif($spj->status_level == 4)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Terverifikasi</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        {{-- Detail selalu ada --}}
                                        <a href="{{ route('operator.spj.show', $spj) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($spj->status_level == 0 || $spj->is_rejected)
                                            {{-- Edit --}}
                                            <a href="{{ route('operator.spj.edit', $spj) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit SPJ">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Ajukan --}}
                                            <form action="{{ route('operator.spj.submit', $spj) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Ajukan SPJ ini untuk persetujuan? Pastikan semua dokumen sudah diunggah.');">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        title="Ajukan ke Persetujuan">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </form>

                                            {{-- Hapus (hanya jika bukan sedang revisi) --}}
                                            @if(!$spj->is_rejected)
                                            <form action="{{ route('operator.spj.destroy', $spj) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus SPJ ini? Tindakan ini tidak dapat dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus SPJ">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    Belum ada SPJ yang dibuat.<br>
                                    <a href="{{ route('operator.spj.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i> Buat SPJ Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
