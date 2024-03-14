let mix = require( 'laravel-mix' );
let argv = require( 'minimist' )( process.argv.slice( 2 ) );

mix.options( {
	processCssUrls: false,
	cssNano: {
		discardComments: {
			removeAll: true,
		},
	},
	manifest: false,
	terser: {
		extractComments: false,
		terserOptions: {
			compress: {
				drop_console: true
			},
			output: {
				comments: false,
			},
		}
	}
} );

mix.js( [ 'assets-src/index.jsx' ], 'assets/js/settings-field-boxes.js' );

if ( ! mix.inProduction() ) {
	mix.sourceMaps();
}

if ( argv.watch ) {
    mix.browserSync({
            files: [
                'assets/js/settings-field-boxes.js',
            ]
        }
    );
}
