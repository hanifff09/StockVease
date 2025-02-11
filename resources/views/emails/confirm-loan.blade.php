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
        .important-notice {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Konfirmasi Peminjaman Barang</h2>
        </div>
        
        <div class="content">
            <p>Kepada Yth. {{ $name }},</p>
            
            <p>Dengan senang hati kami informasikan bahwa pengajuan peminjaman barang Anda telah disetujui dan dikonfirmasi.</p>
            
            <div class="important-notice">
                <strong>Silakan mengambil barang di ruangan peminjaman.</strong>
            </div>
            
            <p>Detail peminjaman:</p>
            <ul>
                <li>Item: {{ $item }}</li>
                <li>Tanggal Peminjaman: {{ $tanggal_peminjaman }}</li>
                <li>Tanggal Pengembalian: {{ $tanggal_pengembalian }}</li>
                <li>Alasan Peminjaman: {{ $alasan_pinjam }}</li>
            </ul>
            
            <p>Mohon perhatikan hal-hal berikut:</p>
            <ul>
                <li>Harap membawa identitas saat pengambilan barang</li>
                <li>Jaga kondisi barang dengan baik</li>
                <li>Kembalikan barang sesuai dengan tanggal yang telah ditentukan</li>
            </ul>
            
            <p>Terima kasih atas kerjasamanya.</p>
        </div>
        
        <div class="footer">
            <p>Ini adalah email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>