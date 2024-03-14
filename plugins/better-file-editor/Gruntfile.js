module.exports = function( grunt ) {
	grunt.initConfig({
		pkg: grunt.file.readJSON( 'package.json' ),

		jshint: {
			all: [
				'Gruntfile.js',
				'assets/js/better-file-editor.js',
				'assets/js/test/**/*.js'
			],
			options: {
				'boss':     true,
				'curly':    true,
				'eqeqeq':   true,
				'eqnull':   true,
				'es3':      true,
				'expr':     true,
				'immed':    true,
				'noarg':    true,
				'onevar':   true,
				'quotmark': 'single',
				'trailing': true,
				'undef':    true,
				'unused':   true,
				'browser':  true,
				'multistr': true,
				'globals': {
					'_':            false,
					'Backbone':     false,
					'jQuery':       false,
					'JSON':         false,
					'wp':           false,
					'module':       true
				}
			}
		},

		uglify: {
			all: {
				files: {
					'assets/js/better-file-editor.min.js': ['assets/js/better-file-editor.js']
				},
				options: {
					banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
						' * <%= pkg.homepage %>\n' +
						' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
						' * License: GPLv2+' +
						' */\n',
					mangle: {
						except: [
							'document',
							'localStorage',
							'jQuery',
							'ace'
						]
					}
				}
			}
		},

		test: {
			files: ['assets/js/test/**/*.js']
		},

		sass: {
			all: {
				files: {
					'assets/css/better-file-editor.css': 'assets/css/better-file-editor.scss'
				}
			}
		},

		cssmin: {
			options: {
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
					' * <%= pkg.homepage %>\n' +
					' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
					' * License: GPLv2+' +
					' */\n'
			},
			minify: {
				expand: true,

				cwd: 'assets/css/',
				src: ['better-file-editor.css'],

				dest: 'assets/css/',
				ext: '.min.css'
			}
		},

		clean: {
			css: ['assets/css/*.css', '!assets/css/*.min.css']
		},

		watch: {
			sass: {
				files: ['assets/css/*.scss'],
				tasks: ['sass', 'cssmin'],
				options: {
					debounceDelay: 500
				}
			},
			scripts: {
				files: [
					'assets/js/ace/**/*.js',
					'assets/js/better-file-editor.js'
				],
				tasks: ['jshint', 'uglify'],
				options: {
					debounceDelay: 500
				}
			}
		}
	});

	// Load other tasks
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-sass' );

	// Default task.
	grunt.registerTask( 'default', ['jshint', 'uglify', 'sass', 'cssmin', 'clean'] );

	grunt.util.linefeed = '\n';
};
