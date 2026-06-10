@extends('adminlte::page')

@section('title', 'Data Rekening')

@section('content_header')
    <h1>Data Rekening</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Rekening</h3>
        <div class="card-tools">
            <a href="{{ route('master.rekenings.create') }}" class="btn btn-primary btn-sm">Tambah Rekening</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Kode Rekening</th>
                        <th>Nama Rekening</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekenings as $rekening)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rekening->kode_rekening }}</td>
                            <td>{{ $rekening->nama_rekening }}</td>
                            <td>
                                <a href="{{ route('master.rekenings.edit', $rekening->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.rekenings.destroy', $rekening->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data rekening.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
