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
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;" class="text-center align-middle">No</th>
                            <th style="width: 28%;" class="text-left align-middle">Deskripsi</th>
                            <th style="width: 17%;" class="text-left align-middle">Jenis SPJ</th>
                            <th style="width: 15%;" class="text-left align-middle">Nominal</th>
                            <th style="width: 17%;" class="text-left align-middle">Status</th>
                            <th style="width: 18%;" class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="spjTableBody">
                        @forelse($spjs as $index => $spj)
                            <tr class="spj-row" data-deskripsi="{{ strtolower($spj->deskripsi) }}" data-tipe="{{ $spj->filter_tipe }}">

                                <td class="text-center text-muted align-middle">{{ $spjs->firstItem() + $index }}</td>


                                <td class="text-left align-middle">
                                    <span class="fw-semibold text-wrap">{{ $spj->deskripsi }}</span>
                                </td>


                                <td class="text-left align-middle">{{ $spj->jenisSpj->nama_jenis }}</td>


                                <td class="text-left align-middle font-monospace text-nowrap">
                                    Rp {{ number_format($spj->nominal, 0, ',', '.') }}
                                </td>


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
                                        <span class="badge bg-info text-dark"><i class="fas fa-check-double mr-2"></i>Disetujui Kadin</span>
                                    @elseif($spj->status_level == 5)
                                        <span class="badge bg-success"><i class="fas fa-check-circle mr-2"></i>Terverifikasi</span>
                                    @endif
                                </td>


                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center flex-wrap" style="gap: 8px;">

                                        @if($spj->status_level > 0 && !$spj->is_rejected)
                                            <a href="{{ route('operator.spj.show', $spj) }}"
                                               class="btn btn-sm btn-info text-white"
                                               style="min-width: 90px;"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>
                                        @else
                                            <a href="{{ route('operator.spj.show', $spj) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        @if($spj->status_level == 0 || $spj->is_rejected)

                                            <a href="{{ route('operator.spj.edit', $spj) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit SPJ">
                                                <i class="fas fa-edit"></i>
                                            </a>


                                            <form id="submit-form-{{ $spj->id }}" action="{{ route('operator.spj.submit', $spj) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                            <button type="button"
                                                    class="btn btn-sm btn-success"
                                                    title="Ajukan ke Persetujuan"
                                                    data-swal-submit="#submit-form-{{ $spj->id }}"
                                                    data-swal-title="Konfirmasi Pengajuan"
                                                    data-swal-type="submit"
                                                    data-swal-html='<p>Anda akan mengajukan SPJ <strong>&quot;{{ Str::limit($spj->deskripsi, 80) }}&quot;</strong> untuk tahapan persetujuan.</p><div class="alert alert-warning text-left mb-0 mt-2" style="font-size:0.88rem;"><i class="fas fa-exclamation-triangle mr-1"></i> <strong>Perhatian:</strong> Pastikan semua dokumen bukti pendukung wajib sudah diunggah dengan lengkap.</div>'>
                                                <i class="fas fa-paper-plane"></i>
                                            </button>


                                            <form action="{{ route('operator.spj.destroy', $spj) }}" method="POST" class="m-0 p-0"
                                                  data-confirm="SPJ ini akan dihapus secara permanen dan tidak dapat dikembalikan."
                                                  data-confirm-title="Hapus SPJ ini?"
                                                  data-confirm-type="danger">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus SPJ">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($spj->status_level == 1)
                                            <form action="{{ route('operator.spj.cancel', $spj) }}" method="POST" class="m-0 p-0"
                                                  data-confirm="Anda yakin ingin menarik pengajuan ini kembali ke status Draft?"
                                                  data-confirm-title="Tarik Ajuan?"
                                                  data-confirm-type="warning">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-warning text-dark"
                                                        title="Tarik Ajuan">
                                                    <i class="fas fa-undo mr-1"></i> Tarik Ajuan
                                                </button>
                                            </form>
                                        @endif

                                        @if($spj->status_level == 5)
                                            <a href="{{ route('operator.spj.print', $spj) }}" target="_blank"
                                               class="btn btn-sm btn-success"
                                               title="Cetak SPJ (PDF)">
                                                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                                            </a>
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
