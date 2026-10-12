const mix = require('laravel-mix');

mix.options({
    progress: false,
});

/*
 |--------------------------------------------------------------------------
 | Только свой CSS — Bootstrap и FontAwesome с CDN
 |--------------------------------------------------------------------------
 */
mix.styles([
    'resources/assets/admin/css/app.css',
], 'public/assets/admin/css/admin.css');

/*
 |--------------------------------------------------------------------------
 | Только свой JS — jQuery и Bootstrap с CDN
 |--------------------------------------------------------------------------
 */
mix.scripts([
    'resources/assets/admin/js/app.js',
], 'public/assets/admin/js/admin.js');

/*
 |--------------------------------------------------------------------------
 | Копирование картинок
 |--------------------------------------------------------------------------
 */
mix.copyDirectory('resources/assets/admin/img', 'public/assets/admin/img');
