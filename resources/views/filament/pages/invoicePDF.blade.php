<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Tagihan Kost</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .info, .footer { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <br>
        <br>
        <br>
        <h2>ANUGRAH GROUP</h2>
        <p>Jl. Raya Serang - Cibarusah Serang, Kongsi No.33, RT.012/RW.06, Sukadami, Cikarang Sel., Kabupaten Bekasi, Jawa Barat 17530</p>
        <hr>
    </div>

    <div class="info">
        <p><strong>Nama:</strong> {{ $penghuni->nama }}</p>
        <p><strong>Kamar:</strong> {{ $penghuni->dataKamar->nama_kamar }} - {{ $penghuni->dataKamar->lokasi }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Jatuh Tempo</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tagihans as $tagihan)
                <tr>
                    <td>{{ $tagihan->periode }}</td>
                    <td>{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d-m-Y') }}</td>
                    <td>Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($tagihan->status) }}</td>
                    <td>{{ $tagihan->catatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="right">
        <strong>Total: Rp{{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    <div class="footer" style="margin-top:40px;">
        <p>Hormat Kami,</p>
        <br><br>
        <p><strong>Wisma Anugrah Group</strong></p>
    </div>
</body>
</html>
