@extends('adminlte::page')

@section('title', 'Tambah Rekening')

@section('content_header')
    <h1>Tambah Rekening</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Rekening</h3>
    </div>
    <form action="{{ route('master.rekenings.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="kode_rekening">Kode Rekening Anggaran</label>
                <input type="text" name="kode_rekening" class="form-control @error('kode_rekening') is-invalid @enderror" id="kode_rekening" placeholder="Contoh: 5.1.02.01.01.0001" value="{{ old('kode_rekening') }}" required pattern="[0-9.]+" title="Hanya angka dan titik yang diperbolehkan">
                <small class="form-text text-muted">Format: Hanya angka dan titik (misal: 5.1.02.01.01.0001).</small>
                @error('kode_rekening') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group">
                <label for="nama_rekening">Nama Rekening</label>
                <input type="text" name="nama_rekening" class="form-control @error('nama_rekening') is-invalid @enderror" id="nama_rekening" placeholder="Masukkan Nama Rekening" value="{{ old('nama_rekening') }}" required>
                @error('nama_rekening') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('master.rekenings.index') }}" class="btn btn-default">Batal</a>
        </div>
    </form>
</div>
@stop
