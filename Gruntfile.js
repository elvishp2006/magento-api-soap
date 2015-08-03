module.exports = function(grunt) {
	var config = {
		package : grunt.file.readJSON( 'package.json' ),

		concat : {
		    options : {
				separator : ';'
		    },
		    site : {
				src : [
					'<%= package.webroot %>/libs/*.js',
					'<%= package.webroot %>/templates/*.js',
					'<%= package.webroot %>/vendor/*.js',
					'<%= package.webroot %>/app/*.js',
					'<%= package.webroot %>/boot.js'
				],
				dest : '<%= package.webroot %>/built.js',
		    },
  		},

  		jshint: {
			options: {
				jshintrc : true
			},
    		beforeconcat : '<%= concat.site.src %>'
  		},

  		uglify : {
			site : {
				files : {
					'<%= concat.site.dest %>' : '<%= concat.site.dest %>'
				}
			}
    	},

    	sass: {
		    site: {
				options: {
					noCache: true,
					style: 'compressed',
					'sourcemap=none': '',
				},
		    	files: {
		    		'<%= package.stylesheetroot %>/style.css': '<%= package.stylesheetroot %>/style.scss'
		    	}
		    },
			dev: {
				options: {
					style: 'expanded',
				},
				files: {
					'<%= package.stylesheetroot %>/style.css': '<%= package.stylesheetroot %>/style.scss'
				}
			},
		},

		watch: {
		    css : {
		      files : ['assets/stylesheets/**/*.scss'],
		      tasks : ['sass:dev']
		    },
		    script : {
		    	files : '<%= concat.site.src %>',
		    	tasks : ['jshint', 'concat']
		    }
  		},
	};

	grunt.initConfig( config );

	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );

	grunt.registerTask( 'js', ['jshint', 'concat'] );
	grunt.registerTask( 'jsmin', ['jshint', 'concat', 'uglify'] );
	grunt.registerTask( 'deploy', ['jsmin', 'sass:site'] );
};
