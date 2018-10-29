let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts([
    'resources/assets/js/admin/jquery.min.js',
    'resources/assets/js/admin/popper.min.js',
    'resources/assets/js/admin/bootstrap.min.js',
    'resources/assets/js/admin/perfect-scrollbar.jquery.min.js',
    'resources/assets/js/admin/waves.js',
    'resources/assets/js/admin/sidebarmenu.js',
    'resources/assets/js/admin/custom.min.js',
    'resources/assets/js/admin/chartist.min.js',
    'resources/assets/js/admin/chartist-plugin-tooltip.min.js',
    'resources/assets/js/admin/d3.min.js',
    'resources/assets/js/admin/c3.min.js',
    'resources/assets/js/admin/dashboard.js',
    'resources/assets/js/user-add.js',





    ],'public/js/admin.js')
    .styles([
        'resources/assets/css/admin/bootstrap.min.css',
        'resources/assets/css/admin/chartist.min.css',
        'resources/assets/css/admin/chartist-plugin-tooltip.css',
        'resources/assets/css/admin/c3.min.css',
        'resources/assets/css/admin/style.css',
        'resources/assets/css/admin/dashboard.css',
        'resources/assets/css/admin/default-dark.css',
        'resources/assets/css/admin/common.css',
        'resources/assets/css/admin/font-awesome.css',
        'resources/assets/css/admin/form-pages.css',
        'resources/assets/css/admin/google-vector.css',
        'resources/assets/css/admin/icon-page.css',
        'resources/assets/css/admin/other-pages.css',
        'resources/assets/css/admin/pages.css',
        'resources/assets/css/admin/pagination.css',
        'resources/assets/css/admin/perfect-scrollbar.css',
        'resources/assets/css/admin/simple-line-icons.css',
        'resources/assets/css/admin/table-pages.css',
        'resources/assets/css/admin/themify-icons.css',
        'resources/assets/css/user-add.css',
        'resources/assets/css/admin/ie7.css'



    ], 'public/css/admin.css');
