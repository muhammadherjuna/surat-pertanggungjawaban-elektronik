@extends('adminlte::page')

@section('title', 'Data Jenis SPJ')

@section('content_header')
    <h1>Data Jenis SPJ</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis SPJ</h3>
        <div class="card-tools">
            <a href="{{ route('master.jenis-spjs.create') }}" class="btn btn-primary btn-sm">Tambah Jenis SPJ</a>
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
                        <th>Nama Jenis SPJ</th>
                        <th>Dokumen Pendukung</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenis_spjs as $jenis_spj)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jenis_spj->nama_jenis }}</td>
                            <td>
                                @if($jenis_spj->dokumenPendukungs->count() > 0)
                                    <ul>
                                        @foreach($jenis_spj->dokumenPendukungs as $doc)
                                            <li>{{ $doc->nama_dokumen }} {!! $doc->is_wajib ? '<span class="badge badge-danger">Wajib</span>' : '<span class="badge badge-info">Opsional</span>' !!}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Tidak ada dokumen pendukung</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('master.jenis-spjs.edit', $jenis_spj->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.jenis-spjs.destroy', $jenis_spj->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data jenis SPJ.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
