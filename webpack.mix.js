const mix = require('laravel-mix');
const fs = require('fs');
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
mix.webpackConfig({
    plugins: [
        {
            apply: (compiler) => {
                compiler.hooks.afterEmit.tap('AfterEmitPlugin', (compilation) => {
                    fs.copyFileSync('public/js/app.js', 'public/js/app_local.js');
                });
            }
        }
    ]
}).setPublicPath('public')
    .js('resources/js/app.js', 'js').vue().version()
