<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pertanggungjawaban - {{ $spj->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; width: 30%; }
        .footer { margin-top: 50px; text-align: right; }
        .qrcode { text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Surat Pertanggungjawaban Elektronik</div>
        <div>Pemerintah Daerah Kota / Kabupaten XXX</div>
    </div>

    <p>Telah diterima dan diverifikasi Surat Pertanggungjawaban (SPJ) dengan rincian sebagai berikut:</p>

    <table>
        <tr>
            <th>ID / UUID SPJ</th>
            <td>{{ $spj->id }} / {{ $spj->uuid }}</td>
        </tr>
        <tr>
            <th>Tanggal Diajukan</th>
            <td>{{ $spj->created_at->format('d F Y H:i') }}</td>
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
            <td>{{ $spj->filter_tipe }} {{ $spj->filter_no ? ' / ' . $spj->filter_no : '' }}</td>
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
            <td>TERVERIFIKASI (Telah Disetujui Berjenjang & Diview Bendahara)</td>
        </tr>
    </table>

    <div class="qrcode">
        <p>Pindai QR Code berikut untuk memverifikasi keabsahan dokumen secara online:</p>
        <img src="data:image/svg+xml;base64,{{ $qrcode }}" alt="QR Code">
        <br>
        <small>{{ url('/public/spj/'.$spj->uuid) }}</small>
    </div>

    <div class="footer">
        <p>Mengesahkan,<br>Bendahara</p>
        <br><br><br>
        <p><strong>{{ Auth::user()->name }}</strong></p>
    </div>
</body>
</html>
