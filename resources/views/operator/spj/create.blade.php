@extends('adminlte::page')

@section('title', 'Buat SPJ Baru')

@section('content_header')
    <h1>Buat SPJ Baru</h1>
@stop

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('operator.spj.store') }}" method="POST">
                @csrf
                
                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Jenis SPJ</label>
                    <select name="jenis_spj_id" class="form-control @error('jenis_spj_id') is-invalid @enderror" required>
                        <option value="" disabled {{ old('jenis_spj_id') ? '' : 'selected' }}>Pilih Jenis SPJ</option>
                        @foreach($jenisSpjs as $js)
                            <option value="{{ $js->id }}" {{ old('jenis_spj_id') == $js->id ? 'selected' : '' }}>{{ $js->nama_jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_spj_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Tuliskan deskripsi/keperluan SPJ..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 form-group">
                        <label class="form-label font-weight-bold">Tipe SPJ</label>
                        <select name="filter_tipe" id="filter_tipe" class="form-control @error('filter_tipe') is-invalid @enderror" required>
                            <option value="GU" {{ old('filter_tipe') == 'GU' ? 'selected' : '' }}>Ganti Uang (GU)</option>
                            <option value="TU" {{ old('filter_tipe') == 'TU' ? 'selected' : '' }}>Tambah Uang (TU)</option>
                        </select>
                        @error('filter_tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 form-group" id="filter_no_container">
                        <label class="form-label font-weight-bold">Nomor Urut (Khusus GU)</label>
                        <select name="filter_no" id="filter_no" class="form-control @error('filter_no') is-invalid @enderror">
                            <option value="" disabled {{ old('filter_no') ? '' : 'selected' }}>Pilih Nomor Urut</option>
                            @for($i = 1; $i <= 24; $i++)
                                <option value="{{ $i }}" {{ old('filter_no') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('filter_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label font-weight-bold">Nominal (Rp)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold">Rp</span>
                        </div>
                        <input type="text" name="nominal" id="nominal" class="form-control font-weight-bold @error('nominal') is-invalid @enderror" value="{{ old('nominal') }}" placeholder="0" required>
                        @error('nominal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label font-weight-bold">Rekening</label>
                    <select name="rekening_id" class="form-control @error('rekening_id') is-invalid @enderror" required>
                        <option value="" disabled {{ old('rekening_id') ? '' : 'selected' }}>Pilih Rekening Tujuan</option>
                        @foreach($rekenings as $rek)
                            <option value="{{ $rek->id }}" {{ old('rekening_id') == $rek->id ? 'selected' : '' }}>{{ $rek->kode_rekening }} - {{ $rek->nama_rekening }}</option>
                        @endforeach
                    </select>
                    @error('rekening_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr>
                <div class="d-flex justify-content-end" style="gap: 10px;">
                    <a href="{{ route('operator.spj.index') }}" class="btn btn-light border btn-action"><i class="fas fa-times mr-2"></i> Batal</a>
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-action"><i class="fas fa-save mr-2"></i> Simpan SPJ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-action {
        transition: all 0.2s ease-in-out;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@stop

@section('js')
<script>
    var nominal = document.getElementById('nominal');
    nominal.addEventListener('keyup', function(e) {
        nominal.value = formatRupiah(this.value);
    });

    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split             = number_string.split(','),
        sisa              = split[0].length % 3,
        rupiah            = split[0].substr(0, sisa),
        ribuan            = split[0].substr(sisa).match(/\d{3}/gi);

        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    var filterTipe = document.getElementById('filter_tipe');
    var filterNoContainer = document.getElementById('filter_no_container');
    var filterNo = document.getElementById('filter_no');

    function toggleFilterNo() {
        if (filterTipe.value === 'TU') {
            filterNoContainer.style.display = 'none';
            filterNo.value = ''; // clear value
            filterNo.removeAttribute('required');
        } else {
            filterNoContainer.style.display = 'block';
            filterNo.setAttribute('required', 'required');
        }
    }

    filterTipe.addEventListener('change', toggleFilterNo);
    toggleFilterNo();

    const form = document.getElementById('btnSubmit').closest('form');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            return;
        }
        var btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        btn.classList.add('disabled');
        btn.style.pointerEvents = 'none';
        
        // Delay disabling to allow form submission to process
        setTimeout(function() {
            btn.disabled = true;
        }, 50);
    });
</script>
@stop
