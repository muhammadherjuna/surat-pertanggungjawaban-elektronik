@extends('adminlte::page')

@section('title', 'Kelola Bidang')

@section('content_header')
    <h1>Kelola Bidang</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Bidang</h3>
            <div class="card-tools">
                <a href="{{ route('master.bidangs.create') }}" class="btn btn-primary btn-sm">Tambah Bidang</a>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped table-hover text-nowrap">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama Bidang</th>
                        <th>Unit Kerja</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bidangs as $index => $bidang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bidang->nama_bidang }}</td>
                        <td>{{ $bidang->unit_kerja ?? '-' }}</td>
                        <td>
                            <a href="{{ route('master.bidangs.edit', $bidang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('master.bidangs.destroy', $bidang->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bidang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data bidang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
