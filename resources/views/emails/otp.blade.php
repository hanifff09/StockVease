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
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
            letter-spacing: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kode OTP Verifikasi Email</h1>
        <p>Halo {{ $name }},</p>
        <p>Terima kasih telah menggunakan layanan peminjaman kami.</p>
        <p>Kode OTP Anda adalah:</p>
        <div class="otp-code">{{ $otpCode }}</div>
        <p>Kode OTP ini akan kadaluarsa dalam 15 menit.</p>
        <p>Jangan bagikan kode ini kepada siapapun.</p>
    </div>
</body>
</html>