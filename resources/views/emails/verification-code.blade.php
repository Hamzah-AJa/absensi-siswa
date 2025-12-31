<!DOCTYPE html>
<html>
<head>
    <title>Kode Verifikasi</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; text-align: center; color: white; border-radius: 10px;">
        <h1 style="margin: 0; font-size: 24px;">Kode Verifikasi Email</h1>
    </div>
    <div style="padding: 40px 20px; background: #f8f9fa;">
        <h2 style="color: #333;">Kode verifikasi Anda:</h2>
        <div style="background: #667eea; color: white; font-size: 36px; font-weight: bold; letter-spacing: 10px; padding: 20px; text-align: center; border-radius: 10px; margin: 30px 0;">
            {{ $code }}
        </div>
        <p style="color: #666; font-size: 16px;">
            Masukkan kode ini di aplikasi untuk memverifikasi email Anda. Kode berlaku <strong>10 menit</strong>.
        </p>
        <hr style="border: none; height: 1px; background: #eee; margin: 40px 0;">
        <p style="color: #999; font-size: 14px; text-align: center;">
            Jika Anda tidak meminta kode ini, abaikan email ini.
        </p>
    </div>
</body>
</html>