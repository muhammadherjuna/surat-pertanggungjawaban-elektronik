@extends('adminlte::page')

@section('title', 'Daftar SPJ Saya')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar SPJ Saya</h1>
        <a href="{{ route('operator.spj.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> Buat SPJ Baru
        </a>
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

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <form onsubmit="return false;" class="form-inline">
                @if(request()->has('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-search"></i></div>
                    </div>
                    <input type="text" id="searchInput" class="form-control" placeholder="Ketik deskripsi..." value="{{ request('search') }}">
                </div>
                
                <div class="input-group mb-2 mr-sm-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-filter"></i></div>
                    </div>
                    <select id="tipeFilter" class="form-control">
                        <option value="">Semua Tipe SPJ</option>
                        <option value="GU" {{ request('tipe') == 'GU' ? 'selected' : '' }}>Ganti Uang (GU)</option>
                        <option value="TU" {{ request('tipe') == 'TU' ? 'selected' : '' }}>Tambah Uang (TU)</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-top mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 4%;" class="text-left align-middle">No</th>
                            <th style="width: 28%;" class="text-left">Deskripsi</th>
                            <th style="width: 18%;" class="text-left">Jenis SPJ</th>
                            <th style="width: 15%;" class="text-left">Nominal</th>
                            <th style="width: 17%;" class="text-left">Status</th>
                            <th style="width: 18%;" class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="spjTableBody">
                        @forelse($spjs as $index => $spj)
                            <tr class="spj-row" data-deskripsi="{{ strtolower($spj->deskripsi) }}" data-tipe="{{ $spj->filter_tipe }}">
                                {{-- Nomor Urut --}}
                                <td class="text-left text-muted align-middle">{{ $spjs->firstItem() + $index }}</td>

                                {{-- Deskripsi --}}
                                <td class="text-left">
                                    <span class="fw-semibold">{{ $spj->deskripsi }}</span>
                                </td>

                                {{-- Jenis SPJ --}}
                                <td class="text-left align-middle">{{ $spj->jenisSpj->nama_jenis }}</td>

                                {{-- Nominal: rata tengah --}}
                                <td class="text-left font-monospace align-middle">
                                    Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                </td>

                                {{-- Status --}}
                                <td class="text-left align-middle">
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

                                {{-- Aksi --}}
                                <td class="align-middle text-left">
                                    <div class="d-flex justify-content-start flex-wrap" style="gap: 8px;">
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
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    data-toggle="modal" 
                                                    data-target="#submitModal-{{ $spj->id }}" 
                                                    title="Ajukan ke Persetujuan">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>

                                            <!-- Modal Konfirmasi Ajukan -->
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

                                            {{-- Hapus (hanya jika bukan sedang revisi) --}}
                                            @if(!$spj->is_rejected)
                                            <form action="{{ route('operator.spj.destroy', $spj) }}" method="POST" class="m-0 p-0"
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    Belum ada SPJ yang dibuat.<br>
                                    <a href="{{ route('operator.spj.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus mr-2"></i> Buat SPJ Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3 mb-3 px-4">
                <div class="text-muted text-sm">
                    Menampilkan <span class="font-weight-bold">{{ $spjs->firstItem() ?? 0 }}</span> sampai <span class="font-weight-bold">{{ $spjs->lastItem() ?? 0 }}</span> dari <span class="font-weight-bold">{{ $spjs->total() }}</span> SPJ
                </div>
                <div class="m-0">
                    {{ $spjs->appends(request()->query())->links('pagination::bootstrap-4') }}
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

    // Live Search (Smart Filter)
    $(document).ready(function() {
        function filterTable() {
            var searchText = $('#searchInput').val().toLowerCase();
            var typeFilter = $('#tipeFilter').val();

            $('.spj-row').each(function() {
                var rowDeskripsi = $(this).data('deskripsi');
                var rowTipe = $(this).data('tipe');

                var matchSearch = rowDeskripsi.includes(searchText);
                var matchTipe = (typeFilter === "") || (rowTipe === typeFilter);

                if (matchSearch && matchTipe) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('#searchInput').on('keyup', filterTable);
        $('#tipeFilter').on('change', filterTable);
        
        // Panggil saat load untuk memastikan nilai dari parameter URL teraplikasikan di filter JS
        filterTable();
    });
</script>
@stop
