<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Страница не найдена | ShortWay</title>

    {{-- Bootstrap 4.6 (CDN) --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- Свой CSS --}}
    <link rel="stylesheet" href="/assets/admin/css/admin.css">
</head>
<body>
    <div style="text-align: center; padding: 80px 20px;">
        <div style="font-size: 120px; font-weight: 700; color: #fbbf24; line-height: 1;">404</div>
        <h1 style="margin: 16px 0;">Страница не найдена</h1>
        <p style="color: #6b7280; margin-bottom: 32px;">
            Возможно, страница была удалена, перемещена или вы ошиблись в адресе.
        </p>
        <a href="{{ url('/') }}"
           style="display: inline-block; padding: 12px 32px; background: #fbbf24; color: #1a1a1a; font-weight: 600; border-radius: 8px; text-decoration: none;">
            На главную
        </a>
    </div>
</body>
</html>
