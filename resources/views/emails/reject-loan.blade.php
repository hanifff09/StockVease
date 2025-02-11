<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pemberitahuan Penolakan Peminjaman Barang</h2>
        </div>
        
        <div class="content">
            <p>Kepada Yth. {{ $name }},</p>
            
            <p>Dengan hormat kami informasikan bahwa pengajuan peminjaman barang Anda telah kami tinjau. Namun, dengan berbagai pertimbangan, kami belum dapat menyetujui peminjaman tersebut.</p>
            
            <p>Detail peminjaman:</p>
            <ul>
                <li>Item: {{ $item }}</li>
                <li>Tanggal Peminjaman: {{ $tanggal_peminjaman }}</li>
                <li>Tanggal Pengembalian: {{ $tanggal_pengembalian }}</li>
                <li>Alasan Peminjaman: {{ $alasan_pinjam }}</li>
            </ul>
            
            <p>Anda dapat mengajukan peminjaman kembali dengan memberikan alasan yang lebih detail atau mencoba di waktu yang berbeda.</p>
            
            <p>Terima kasih atas pengertian Anda.</p>
        </div>
        
        <div class="footer">
            <p>Ini adalah email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html> 