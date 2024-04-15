let mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set environment variables
mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/cart.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', []);
mix.env(process.env.MIX_MY_VARIABLE);

// // Other mix configurations...
//
// mix.js('resources/js/app.js', 'public/js')
//     .js('resources/js/cart.js', 'public/js')
//     .postCss('resources/css/app.css', 'public/css', [
//         //
//     ]);
