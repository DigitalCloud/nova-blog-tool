let mix = require('laravel-mix')

mix.setPublicPath('dist')
   .js('resources/js/tool.js', 'js')
   .sass('resources/sass/tool.scss', 'css')
   
mix.js('resources/js/post-form.js', 'js')
	.sass('resources/sass/post-form.scss', 'css')
	.webpackConfig({
		resolve: {
			alias: {
				'@nova': path.resolve(__dirname, '../../vendor/laravel/nova/resources/js/')
			},
			symlinks: false
		}
	})
