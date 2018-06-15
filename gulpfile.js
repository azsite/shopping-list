const elixir = require('laravel-elixir');
require('laravel-elixir-vue-2');

elixir( function(mix) {
    mix.sass('app.scss')
    .webpack('app.js')
    .version(['css/app.css', 'js/app.js', 'index.html']);
});