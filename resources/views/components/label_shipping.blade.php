<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 400px;
            border: 2px dashed black;
            padding: 20px;
        }

        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
        }

        .separator {
            border-bottom: 1px dashed black;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('assets/images/logo.png') }}"
            style="width: 400px;">
    </div>

    <div class="section">
        <span class="label">Nomor Resi:</span>
        @if ($dataOrder->nomor_resi)
            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $dataOrder->nomor_resi }}&scale=2&height=12"
                width="400px;">
            <p style="text-align: center;">{{ $dataOrder->nomor_resi }}</p>
        @else
            <p>Belum ada nomor resi</p>
        @endif
    </div>

    <div class="section">
        <span class="label">Total Ongkir:</span>
        <p>Rp {{ number_format($dataOrder->biaya_pengiriman ?? 0, 0, ',', '.') }}</p>
    </div>

    <div class="section">
        <span class="label">Jenis Layanan:</span>
        <p>{{ $dataOrder->metodePengiriman->courier_service_name ?? 'Ambil di tempat' }}</p>
    </div>

    <div class="section">
        <span class="label">Reference Number:</span>
        @if ($dataOrder->unique_order)
            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $dataOrder->unique_order }}&scale=2&height=20"
                width="400px;">
            <p style="text-align: center;">{{ $dataOrder->unique_order }}</p>
        @else
            <p>-</p>
        @endif
    </div>

    <div class="separator"></div>

    <div class="section">
        <span class="label">Alamat Penerima:</span>
        <p>
            {{ substr($dataOrder->cpCustomer->nama, 0, 3) . str_repeat('*', max(0, strlen($dataOrder->cpCustomer->nama) - 3)) }}<br>
            {{ substr($dataOrder->cpCustomer->no_telp, 0, 5) . str_repeat('*', max(0, strlen($dataOrder->cpCustomer->no_telp) - 5)) }}<br>
            @if ($dataOrder->jenis_pengiriman === 'ekspedisi' && $dataOrder->alamatCustomer)
                {{ $dataOrder->alamatCustomer->province->nama }}, {{ $dataOrder->alamatCustomer->city->nama }}<br>
                {{ $dataOrder->alamatCustomer->district->nama }},
                {{ $dataOrder->alamatCustomer->subDistrict->nama }}<br>
                {{ \Str::limit($dataOrder->alamatCustomer->alamat_lengkap, 50) }}
            @else
                Ambil di tempat
            @endif
        </p>
    </div>

    <div class="section">
        <span class="label">Alamat Pengirim:</span>
        <p>
            Perusahaan Jasa Telekomunikasi<br>
            6281214931661<br>
            Jl. Sholeh Iskandar No.KM 6, RT.04/RW.01, Cibadak,<br>
            Kec. Tanah Sereal, Kota Bogor, Jawa Barat 16166
        </p>
    </div>

    <div class="separator"></div>

    <div class="section">
        <span class="label">Jenis Barang:</span>
        <p>
            1x {{ $dataOrder->perangkat->produk->nama_produk ?? 'Produk' }} -
            {{ $dataOrder->perangkat->nama_perangkat ?? 'Perangkat' }} - {{ $dataOrder->layanan->nama_layanan ?? 'Layanan' }}
        </p>
    </div>

    <div class="section">
        <span class="label">Catatan:</span>
        <p>Please be careful</p>
    </div>

    <div class="separator"></div>

    <div class="section" style="text-align: center;">
        <p>Order ID: {{ $dataOrder->unique_order }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($dataOrder->order_date)->format('d/m/Y H:i') }}</p>
    </div>
</body>

</html>
