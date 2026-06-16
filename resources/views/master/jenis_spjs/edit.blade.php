@extends('adminlte::page')

@section('title', 'Edit Jenis SPJ')

@section('content_header')
    <h1>Edit Jenis SPJ</h1>
@stop

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Form Edit Jenis SPJ</h3>
    </div>
    <form action="{{ route('master.jenis-spjs.update', $jenis_spj->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="nama_jenis">Nama Jenis SPJ</label>
                <input type="text" name="nama_jenis" class="form-control @error('nama_jenis') is-invalid @enderror" id="nama_jenis" value="{{ old('nama_jenis', $jenis_spj->nama_jenis) }}" required>
                @error('nama_jenis') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            
            <hr>
            <h4>Dokumen Pendukung</h4>
            <div id="dokumen-container">
                @if($jenis_spj->dokumenPendukungs->count() > 0)
                    @foreach($jenis_spj->dokumenPendukungs as $index => $doc)
                        <div class="row dokumen-row align-items-center mb-2">
                            <input type="hidden" name="dokumen[{{ $index }}][id]" value="{{ $doc->id }}">
                            <div class="col-md-7">
                                <input type="text" name="dokumen[{{ $index }}][nama_dokumen]" class="form-control" value="{{ $doc->nama_dokumen }}" required>
                            </div>
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox mt-2">
                                    <input class="custom-control-input" type="checkbox" id="wajib_{{ $index }}" name="dokumen[{{ $index }}][is_wajib]" value="1" {{ $doc->is_wajib ? 'checked' : '' }}>
                                    <label for="wajib_{{ $index }}" class="custom-control-label">Wajib?</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-dokumen">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row dokumen-row align-items-center mb-2">
                        <div class="col-md-7">
                            <input type="text" name="dokumen[0][nama_dokumen]" class="form-control" placeholder="Nama Dokumen (Contoh: Kuitansi, Nota, dll)">
                        </div>
                        <div class="col-md-3">
                            <div class="custom-control custom-checkbox mt-2">
                                <input class="custom-control-input" type="checkbox" id="wajib_0" name="dokumen[0][is_wajib]" value="1">
                                <label for="wajib_0" class="custom-control-label">Wajib?</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-dokumen" disabled>Hapus</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-success btn-sm mt-2" id="add-dokumen">Tambah Dokumen</button>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('master.jenis-spjs.index') }}" class="btn btn-default">Batal</a>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        let docIndex = {{ max($jenis_spj->dokumenPendukungs->count(), 1) }};
        $('#add-dokumen').click(function() {
            let html = `
                <div class="row dokumen-row align-items-center mb-2">
                    <div class="col-md-7">
                        <input type="text" name="dokumen[${docIndex}][nama_dokumen]" class="form-control" placeholder="Nama Dokumen" required>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-control custom-checkbox mt-2">
                            <input class="custom-control-input" type="checkbox" id="wajib_${docIndex}" name="dokumen[${docIndex}][is_wajib]" value="1">
                            <label for="wajib_${docIndex}" class="custom-control-label">Wajib?</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-dokumen">Hapus</button>
                    </div>
                </div>
            `;
            $('#dokumen-container').append(html);
            docIndex++;
        });

        $(document).on('click', '.remove-dokumen', function() {
            $(this).closest('.dokumen-row').remove();
        });
    });
</script>
@stop
