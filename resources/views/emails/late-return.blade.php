<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Peringatan Pengembalian Barang</title>
</head>
<body>
    <h2>Peringatan Pengembalian Barang</h2>
    <p>Yth. {{ $name }},</p>
    <p>Dengan hormat kami informasikan bahwa peminjaman barang berikut telah melewati batas waktu pengembalian:</p>
    <ul>
        <li>Item: {{ $item }}</li>
        <li>Tanggal Peminjaman: {{ $tanggal_peminjaman }}</li>
        <li>Tanggal Pengembalian Seharusnya: {{ $tanggal_pengembalian }}</li>
    </ul>
    <p>Mohon segera mengembalikan barang tersebut.</p>
    <p>Terima kasih atas perhatian dan kerjasamanya.</p>
</body>
</html>