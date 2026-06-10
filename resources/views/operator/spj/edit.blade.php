@extends('adminlte::page')

@section('title', 'Edit SPJ')

@section('content_header')
    <h1>Edit SPJ</h1>
@stop

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('operator.spj.update', $spj) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Jenis SPJ</label>
                    <select name="jenis_spj_id" class="form-select @error('jenis_spj_id') is-invalid @enderror" required>
                        <option value="" disabled {{ old('jenis_spj_id', $spj->jenis_spj_id) ? '' : 'selected' }}>-- Pilih Jenis SPJ --</option>
                        @foreach($jenisSpjs as $js)
                            <option value="{{ $js->id }}" {{ (old('jenis_spj_id', $spj->jenis_spj_id) == $js->id) ? 'selected' : '' }}>{{ $js->nama_jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_spj_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $spj->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Filter Tipe</label>
                        <select name="filter_tipe" id="filter_tipe" class="form-select @error('filter_tipe') is-invalid @enderror" required>
                            <option value="GU" {{ old('filter_tipe', $spj->filter_tipe) == 'GU' ? 'selected' : '' }}>GU</option>
                            <option value="TU" {{ old('filter_tipe', $spj->filter_tipe) == 'TU' ? 'selected' : '' }}>TU</option>
                        </select>
                        @error('filter_tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6" id="filter_no_container">
                        <label class="form-label">Filter No</label>
                        <select name="filter_no" id="filter_no" class="form-select @error('filter_no') is-invalid @enderror">
                            <option value="" disabled {{ old('filter_no', $spj->filter_no) ? '' : 'selected' }}>-- Pilih Nomor --</option>
                            @for($i = 1; $i <= 24; $i++)
                                <option value="{{ $i }}" {{ old('filter_no', $spj->filter_no) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('filter_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nominal (Rp)</label>
                    <input type="text" name="nominal" id="nominal" class="form-control @error('nominal') is-invalid @enderror" value="{{ old('nominal', number_format($spj->nominal, 0, ',', '.')) }}" required>
                    @error('nominal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Rekening</label>
                    <select name="rekening_id" class="form-select @error('rekening_id') is-invalid @enderror" required>
                        <option value="" disabled {{ old('rekening_id', $spj->rekening_id) ? '' : 'selected' }}>-- Pilih Rekening --</option>
                        @foreach($rekenings as $rek)
                            <option value="{{ $rek->id }}" {{ (old('rekening_id', $spj->rekening_id) == $rek->id) ? 'selected' : '' }}>{{ $rek->kode_rekening }} - {{ $rek->nama_rekening }}</option>
                        @endforeach
                    </select>
                    @error('rekening_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Perbarui SPJ</button>
                <a href="{{ route('operator.spj.show', $spj) }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
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
    toggleFilterNo(); // run on load
</script>
@stop
