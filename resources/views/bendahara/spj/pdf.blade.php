<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pertanggungjawaban - {{ $spj->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; line-height: 1.6; color: #333; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 8px; }
        .header .instansi { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header .dinas { font-size: 14px; font-weight: bold; text-transform: uppercase; }
        .header .alamat { font-size: 10px; color: #555; margin-top: 4px; line-height: 1.4; }
        .doc-title { text-align: center; margin: 18px 0 6px; font-size: 15px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; text-decoration: underline; }
        .pengantar { margin-bottom: 15px; font-size: 12.5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #444; padding: 7px 10px; text-align: left; font-size: 12.5px; }
        th { background-color: #f5f5f5; width: 28%; font-weight: bold; }
        .qrcode { text-align: center; margin-top: 25px; }
        .qrcode p { font-size: 11px; color: #555; margin-bottom: 8px; }
        .qrcode small { font-size: 9px; color: #888; word-break: break-all; }
        .signature { margin-top: 40px; text-align: center; }
        .signature .title { font-size: 12px; margin-bottom: 60px; }
        .signature .name { font-size: 13px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <div class="instansi">Pemerintah Daerah Kabupaten Kebumen</div>
        <div class="dinas">Dinas Komunikasi dan Informatika</div>
        <div class="alamat">
            Jl. K.H. Hasyim Asy'ari No.6, Panjer, Kec. Kebumen, Kabupaten Kebumen, Jawa Tengah 54312<br>
            Telp: (0287) 383349
        </div>
    </div>

    <div class="doc-title">Surat Pertanggungjawaban Elektronik</div>

    <p class="pengantar">Telah diterima dan diverifikasi Surat Pertanggungjawaban (SPJ) dengan rincian sebagai berikut:</p>

    @php
        $bulanIndonesia = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tanggalDiajukan = $spj->submitted_at ?? $spj->created_at;
        $tanggalFormatted = $tanggalDiajukan->format('d') . ' ' . $bulanIndonesia[(int)$tanggalDiajukan->format('m')] . ' ' . $tanggalDiajukan->format('Y');
    @endphp

    <table>
        <tr>
            <th>Tanggal Diajukan</th>
            <td>{{ $tanggalFormatted }}</td>
        </tr>
        <tr>
            <th>Pengaju</th>
            <td>{{ $spj->user->name }}</td>
        </tr>
        <tr>
            <th>Jenis SPJ</th>
            <td>{{ $spj->jenisSpj->nama_jenis }}</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>{{ $spj->deskripsi }}</td>
        </tr>
        <tr>
            <th>Tipe / No</th>
            <td>{{ $spj->filter_tipe }}{{ $spj->filter_no ? ' / ' . $spj->filter_no : '' }}</td>
        </tr>
        <tr>
            <th>Rekening</th>
            <td>{{ $spj->rekening->kode_rekening }} - {{ $spj->rekening->nama_rekening }}</td>
        </tr>
        <tr>
            <th>Nominal</th>
            <td>Rp {{ number_format($spj->nominal, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><strong>TERVERIFIKASI</strong></td>
        </tr>
    </table>

    <div class="qrcode">
        <p>Pindai QR Code berikut untuk memverifikasi keabsahan dokumen secara online:</p>
        <img src="data:image/svg+xml;base64,{{ $qrcode }}" alt="QR Code">
        <br>
        <small>{{ url('/public/spj/'.$spj->uuid) }}</small>
    </div>

    <div class="signature">
        <div class="title">Mengesahkan,<br>Bendahara Dinas</div>
        <div class="name">{{ Auth::user()->name }}</div>
    </div>
</body>
</html>
