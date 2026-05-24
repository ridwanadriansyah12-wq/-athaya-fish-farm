<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Athaya Fish Farm</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #F3F4F6;
            color: #121212;
            padding: 30px 16px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #101216 0%, #1e2530 100%);
            padding: 32px 32px 24px;
            border-radius: 16px 16px 0 0;
            text-align: center;
        }
        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #D4AF37, #E8C34B);
            border-radius: 14px;
            margin-bottom: 16px;
        }
        .header h1 {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .header p {
            color: rgba(255,255,255,.55);
            font-size: 13px;
        }
        .body {
            background: #fff;
            padding: 32px;
            border-left: 1px solid #E5E7EB;
            border-right: 1px solid #E5E7EB;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #101216;
            margin-bottom: 12px;
        }
        .text {
            font-size: 14px;
            color: #4B5563;
            line-height: 1.7;
            margin-bottom: 16px;
        }
        .btn-wrap {
            text-align: center;
            margin: 28px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #D4AF37 0%, #E8C34B 100%);
            color: #101216 !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 36px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(212,175,55,0.35);
        }
        .divider {
            border: none;
            border-top: 1px solid #F0F0F0;
            margin: 24px 0;
        }
        .link-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 12px;
            color: #6B7280;
            word-break: break-all;
        }
        .warning {
            font-size: 13px;
            color: #6B7280;
            margin-top: 16px;
            padding: 12px 16px;
            background: #FFF8E1;
            border-left: 3px solid #F59E0B;
            border-radius: 6px;
        }
        .footer {
            background: linear-gradient(135deg, #101216 0%, #1e2530 100%);
            padding: 20px 32px;
            border-radius: 0 0 16px 16px;
            text-align: center;
        }
        .footer p {
            color: rgba(255,255,255,.45);
            font-size: 12px;
            line-height: 1.6;
        }
        .footer a {
            color: #D4AF37;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- Header --}}
        <div class="header">
            <div class="logo-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="#101216" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L4 6v6c0 5.25 3.4 10.15 8 11.35C16.6 22.15 20 17.25 20 12V6l-8-4z"/>
                </svg>
            </div>
            <h1>Reset Password</h1>
            <p>Athaya Fish Farm</p>
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">Halo,</p>
            <p class="text">
                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda
                di <strong>Athaya Fish Farm</strong>.
            </p>
            <p class="text">
                Klik tombol di bawah ini untuk membuat password baru. Link ini akan kedaluwarsa dalam
                <strong>60 menit</strong>.
            </p>

            <div class="btn-wrap">
                <a href="{{ $actionUrl }}" class="btn" target="_blank">
                    🔑 &nbsp; Reset Password Sekarang
                </a>
            </div>

            <hr class="divider">

            <p class="text" style="font-size:13px;margin-bottom:8px;">
                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:
            </p>
            <div class="link-box">{{ $actionUrl }}</div>

            <div class="warning">
                ⚠️ Jika Anda tidak merasa meminta reset password, abaikan email ini — password Anda tidak akan berubah.
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>
                © {{ date('Y') }} Athaya Fish Farm. Semua hak dilindungi.<br>
                <a href="{{ url('/') }}">Kunjungi Website Kami</a>
            </p>
        </div>
    </div>
</body>
</html>
