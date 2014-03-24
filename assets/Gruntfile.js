(function(){
		'use strict';

		module.exports = function (grunt) {

				/*
						Grunt installation:
						-------------------
								npm install -g grunt-cli
								npm install -g grunt-init

						Project Dependencies:
						---------------------
								npm install grunt --save-dev
								npm install load-grunt-tasks --save-dev
								npm install grunt-contrib-jshint --save-dev
								npm install grunt-contrib-concat --save-dev
								npm install grunt-contrib-uglify --save-dev

								npm install grunt-contrib-watch --save-dev (installed to overcome listener bug on sass 3.3.0)
								npm install grunt-contrib-sass --save-dev

				*/

				// load all grunt tasks
				require('load-grunt-tasks')(grunt);

				// Project configuration.
				grunt.initConfig({

						// Store your Package file so you can reference its specific data whenever necessary
						pkg: grunt.file.readJSON('package.json'),

						// configurable paths
						paths: {
								app:				'.',
								sass:				'./scss',
								css:				'./css',
								js:					'./js',
								images:			'./images',
								svg:				'./images/svg'								
							},

						// test individual js files before combining them for output
							jshint: {
								files: [
										'Gruntfile.js',
										'<%= paths.js %>/base/base.js'
									],
									options: {
										curly:   false,
										eqeqeq:  true,
										immed:   true,
										latedef: false,
										newcap:  false,
										noarg:   true,
										sub:     true,
										undef:   false,
										boss:    true,
										eqnull:  true,
										browser: true,
										indent:  4,
										loopfunc: true,

										globals: {
												// AMD
												module:     true,
												require:    true,
												requirejs:  true,
												define:     true,
												// Environments
												console:    true,
												// General Purpose Libraries
												$:          true,
												jQuery:     true,

												// Testing
												sinon:      true,
												describe:   true,
												it:         true,
												expect:     true,
												beforeEach: true,
												afterEach:  true
											}
										}
									},

									uglify : {
										js: {
											files: {
												'<%= paths.js %>/base/base.min.js' : [ '<%= paths.js %>/base/base.js' ]
											}
										}
									},

									watch: {
										sass: {
											files: ['<%= paths.sass %>/**/*.{scss,sass}'],
											tasks: ['sass:dist']
										}
									},

									sass: {
										dist: {
											files: {
												'<%= paths.css %>/base.css': '<%= paths.sass %>/base.scss',
												'<%= paths.css %>/app.css': '<%= paths.sass %>/app.scss'
											},
											options: {
												sourcemap: 'true',
												style: 'compressed'
											}
										}
									},

									svg2png: {
										all: {
											// specify files in array format with multiple src-dest mapping
											files: [
												// rasterize all SVG files in "img" and its subdirectories to "img/png"
												{ src: ['<%= paths.svg %>/**/*.svg'], dest: '<%= paths.images %>/png/' }
											]
										}
									}

								});

				// Default Task
				grunt.registerTask('default', [
						'sass',
						'uglify',
						'svg2png'
					]);

				
				// Testing
				grunt.registerTask('test', [
						'jshint'
					]);

				// (helper task)
				grunt.registerTask('dev', [
						'jshint',
						'watch'
					]);
			
			};

	}());