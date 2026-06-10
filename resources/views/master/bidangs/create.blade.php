@extends('adminlte::page')

@section('title', 'Tambah Bidang')

@section('content_header')
    <h1>Tambah Bidang</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <form action="{{ route('master.bidangs.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_bidang">Nama Bidang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_bidang') is-invalid @enderror" id="nama_bidang" name="nama_bidang" placeholder="Masukkan nama bidang" value="{{ old('nama_bidang') }}" required>
                            @error('nama_bidang')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="unit_kerja">Unit Kerja</label>
                            <input type="text" class="form-control @error('unit_kerja') is-invalid @enderror" id="unit_kerja" name="unit_kerja" placeholder="Masukkan unit kerja" value="{{ old('unit_kerja') }}">
                            @error('unit_kerja')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('master.bidangs.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
