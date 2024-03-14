/* jshint node:true */

module.exports = function( grunt ){
	'use strict';

	grunt.initConfig({

		// setting folder templates
		dirs: {
			css: 'plugin-assets/css',
			less: 'plugin-assets/css',
			js: 'plugin-assets/js'
		},

		// Compile all .less files.
		less: {
			compile: {
				options: {
					// These paths are searched for @imports
					paths: ['<%= less.css %>/']
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.css %>/',
					src: [
						'*.less',
						'!mixins.less'
					],
					dest: '<%= dirs.css %>/',
					ext: '.css'
				}]
			}
		},

		// Minify all .css files.
		cssmin: {
			minify: {
				expand: true,
				cwd: '<%= dirs.css %>/',
				src: ['*.css'],
				dest: '<%= dirs.css %>/',
				ext: '.css'
			}
		},

		// Minify .js files.
		uglify: {
			options: {
				preserveComments: 'false'
			},
			jsfiles: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/',
					src: [
						'*.js',
						'!*.min.js',
						'!Gruntfile.js',
					],
					dest: '<%= dirs.js %>/',
					ext: '.min.js'
				}]
			}
		},

		concat: {
			options: {
				separator: ';',
			},
			dist: {
				src: ['<%= dirs.js %>/*.min.js'],
				dest: '<%= dirs.js %>/frontend.min.js',
			},
		},

		// Watch changes for assets
		watch: {
			less: {
				files: [
					'<%= dirs.less %>/*.less',
				],
				tasks: ['less', 'cssmin', 'concat'],
			},
			js: {
				files: [
					'<%= dirs.js %>/*js',
					'!<%= dirs.js %>/*.min.js'
				],
				tasks: ['uglify']
			}
		},

	});

	// Load NPM tasks to be used here
	grunt.loadNpmTasks( 'grunt-contrib-less' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks('grunt-contrib-concat');

	// Register tasks
	grunt.registerTask( 'default', [
		'less',
		'cssmin',
		'uglify',
		'concat',
		'watch'
	]);

};
