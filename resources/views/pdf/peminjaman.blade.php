<!DOCTYPE html>
<html>
<head>
    <title>Rekap Data Peminjaman</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-right: 20px;
        }
        .header h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .period {
            background: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
            margin-bottom: 20px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4a90e2;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            color: #666;
            font-style: italic;
        }
        .foter {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .foter h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }
        .status-dipinjam {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-dikembalikan {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-terlambat {
            background-color: #fee2e2;
            color: #991b1b;
        }
        @media print {
            body {
                padding: 0;
            }
            .header {
                background: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            {{-- <img src="public/media/logos/logotp.png" alt="StockVease Logo" class="logo"> --}}
            <h2>Rekap Data Peminjaman</h2>
        </div>
    </div>
    
    <div class="period">
        <strong>Periode:</strong> {{ $bulan }} {{ $tahun }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIP</th>
                <th>Item</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->nip }}</td>
                    <td>{{ $item->item }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->tanggal_peminjaman)) }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->tanggal_pengembalian)) }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($item->text_status) }}">
                            {{ $item->text_status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    <div class="foter">
        <h3>StockVease</h3>
        <span class="text-black-50">&copy; 2024 StockVease. All Rights Reserved.</span>
    </div>
</body>
</html>