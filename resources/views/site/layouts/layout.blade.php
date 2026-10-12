<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ShortWay — магазин игр')</title>

    {{-- Bootstrap 4.6 (CDN) --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- FontAwesome 6 (CDN) --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Свой CSS --}}
    <link rel="stylesheet" href="/assets/admin/css/admin.css">

    <style>
        * { box-sizing: border-box; }
        body {
            background: #1a1a1a;
            color: #e5e5e5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
        }
        .site-header {
            background: #0f0f0f;
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #2a2a2a;
        }
        .site-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
            font-weight: 700;
            color: #fbbf24;
            text-decoration: none;
        }
        .site-logo img { max-height: 36px; }
        .site-nav a {
            color: #e5e5e5;
            text-decoration: none;
            margin-left: 24px;
            font-size: 15px;
            transition: color .2s;
        }
        .site-nav a:hover { color: #fbbf24; }
        .site-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 40px;
            min-height: calc(100vh - 200px);
        }
        .site-footer {
            background: #0f0f0f;
            color: #6b7280;
            text-align: center;
            padding: 20px;
            border-top: 1px solid #2a2a2a;
            font-size: 14px;
        }
        .hero { text-align: center; padding: 40px 0 60px; }
        .hero h1 { font-size: 42px; color: #fbbf24; margin: 0 0 16px; }
        .hero p { font-size: 18px; color: #9ca3af; max-width: 640px; margin: 0 auto 32px; }
        .hero-btn {
            display: inline-block;
            padding: 14px 40px;
            background: #fbbf24;
            color: #1a1a1a;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: background .2s;
        }
        .hero-btn:hover { background: #f59e0b; color: #1a1a1a; text-decoration: none; }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        .feature-card {
            background: #0f0f0f;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            padding: 24px;
        }
        .feature-card h3 { color: #fbbf24; margin: 0 0 12px; font-size: 18px; }
        .feature-card p { color: #9ca3af; margin: 0; font-size: 14px; }
    </style>
</head>
<body>

    <header class="site-header">
        <a href="{{ url('/') }}" class="site-logo">
            <img src="{{ asset('assets/admin/img/shortway-logo.png') }}" alt="ShortWay">
            ShortWay
        </a>
        <nav class="site-nav">
            <a href="{{ url('/') }}">Главная</a>
            <a href="#">Новинки</a>
            <a href="#">Каталог</a>
            <a href="#">Аккаунт</a>
        </nav>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} ShortWay. Все права защищены.
    </footer>

</body>
</html>
