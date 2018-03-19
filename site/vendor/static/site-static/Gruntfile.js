'use strict';

module.exports = function (grunt) {

    // Modules
    // -----------------------------------------------------------------------------------------------------------------
    require('jit-grunt')(grunt, {
        useminPrepare: 'grunt-usemin'
    });

    // Application
    // -----------------------------------------------------------------------------------------------------------------
    var appConfig = {
        src: 'src',
        dist: 'dist',
        tmp: '.tmp'
    };

    // Init Config
    // -----------------------------------------------------------------------------------------------------------------
    grunt.initConfig({
        app: appConfig,

        // Module: grunt-contrib-clean
        // -------------------------------------------------------------------------------------------------------------
        clean: {
            dev: ['<%= app.tmp %>'],
            dist: ['<%= app.tmp %>', '<%= app.dist %>']
        },

        // Module: grunt-wiredep
        // -------------------------------------------------------------------------------------------------------------
        wiredep: {
            options: {
                devDependencies: true
            },
            sass: {
                src: ['<%= app.src %>/styles/*.scss']
            },
            all: {
                src: ['<%= app.src %>/templates/{,*/}*.hbs'],
                exclude: ['modernizr'],
                ignorePath: /(\.\.\/){1,2}/
            }
        },

        // Module: grunt-sass
        // -------------------------------------------------------------------------------------------------------------
        sass: {
            options: {
                sourceMap: true,
                sourceMapEmbed: true,
                sourceMapContents: true,
                includePaths: ['.']
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= app.src %>',
                    src: 'styles/*.scss',
                    dest: '<%= app.tmp %>',
                    ext: '.css'
                }]
            }
        },

        // Module: grunt-postcss
        // -------------------------------------------------------------------------------------------------------------
        postcss: {
            options: {
                map: {
                    inline: false
                },
                processors: [
                    require('autoprefixer')({
                        browsers: ['last 2 versions', 'ie 11']
                    })
                ]
            },
            all: {
                src: '<%= app.tmp %>/styles/theme.css'
            }
        },

        // Module: grunt-browserify
        // -------------------------------------------------------------------------------------------------------------
        browserify: {
            options: {
                browserifyOptions: {
                    debug: true
                }
            },
            dev: {
                src: ['<%= app.src %>/scripts/{,*/}*.js'],
                dest: '<%= app.tmp %>/scripts/theme.js'
            },
            player: {
                src: ['<%= app.src %>/scripts/player.js'],
                dest: '<%= app.tmp %>/scripts/player.js'
            }
        },

        // Module: grunt-exorcise
        // -------------------------------------------------------------------------------------------------------------
        exorcise: {
            dev: {
                src: ['<%= app.tmp %>/scripts/theme.js'],
                dest: '<%= app.tmp %>/scripts/theme.js.map'
            },
            player: {
                src: ['<%= app.tmp %>/scripts/player.js'],
                dest: '<%= app.tmp %>/scripts/player.js.map'
            }
        },

        // Module: grunt-contrib-jshint
        // -------------------------------------------------------------------------------------------------------------
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                reporter: require('jshint-stylish')
            },
            all: [
                'Gruntfile.js',
                '<%= app.src %>/scripts/{,*/}*.js'
            ]
        },

        // Module: grunt-contrib-imagemin
        // -------------------------------------------------------------------------------------------------------------
        imagemin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= app.src %>',
                    src: 'images/*.{gif,jp?(e)g,png,svg}',
                    dest: '<%= app.dist %>'
                }]
            }
        },

        // Module: grunt-contrib-copy
        // -------------------------------------------------------------------------------------------------------------
        copy: {
            dev: {
                files: [{
                    expand: true,
                    flatten: true,
                    cwd: '<%= app.src %>/vendor',
                    src: ['**/*.{eot,svg,ttf,woff?(2)}'],
                    dest: '<%= app.src %>/fonts'
                }]
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= app.src %>',
                    src: [
                        'fonts/{,*/}*.{eot,ttf,woff,woff2,svg}',
                        '*.{png,ico,txt}'
                    ],
                    dest: '<%= app.dist %>'
                },{
                    expand: true,
                    cwd: '<%= app.tmp %>',
                    src: '*.html',
                    dest: '<%= app.dist %>'
                }]
            }
        },

        // Module: grunt-assemble
        // -------------------------------------------------------------------------------------------------------------
        assemble: {
            options: {
                layoutdir: '<%= app.src %>/templates/layouts',
                layout: 'default.hbs',
                partials: '<%= app.src %>/templates/partials/*.hbs',
                data: '<%= app.src %>/*.{json,yml}'
            },
            pages: {
                files: [{
                    expand: true,
                    flatten: true,
                    cwd: '<%= app.src %>/templates',
                    src: '*.hbs',
                    dest: '<%= app.tmp %>'
                }]
            }
        },

        // Module: grunt-browser-sync
        // -------------------------------------------------------------------------------------------------------------
        browserSync: {
            dev: {
                options: {
                    notify: false,
                    watchTask: true,
                    server: {
                        baseDir: ['<%= app.tmp %>', '<%= app.src %>']
                    }
                },
                bsFiles: {
                    src: [
                        '<%= app.tmp %>/styles/*.css',
                        '<%= app.tmp %>/scripts/**/*.js',
                        '<%= app.src %>/images/**/*.{gif,jp?(e)g,png,svg,webp}',
                        '<%= app.tmp %>/*.html'
                    ]
                }
            },
            dist: {
                options: {
                    server: '<%= app.dist %>'
                }
            }
        },

        // Module: grunt-contrib-watch
        // -------------------------------------------------------------------------------------------------------------
        watch: {
            wiredep: {
                files: ['bower.json'],
                tasks: ['wiredep']
            },
            styles: {
                files: ['<%= app.src %>/styles/{,*/}*.scss'],
                tasks: ['sass', 'postcss']
            },
            scripts: {
                files: ['<%= app.src %>/scripts/{,*/}*.js'],
                tasks: ['jshint', 'browserify', 'exorcise']
            },
            html: {
                files: ['<%= app.src %>/templates/{,*/}*.hbs', '<%= app.src %>/*.{json,yml}'],
                tasks: ['assemble']
            }
        },

        // Module: grunt-usemin
        // -------------------------------------------------------------------------------------------------------------
        useminPrepare: {
            html: 'dist/index.html'
        },

        usemin: {
            html: ['dist/*.html']
        },

        // Module: grunt-concurrent
        // -------------------------------------------------------------------------------------------------------------
        concurrent: {
            dev: [
                'sass',
                'browserify',
                'assemble',
                'copy:dev'
            ],
            dist: [
                'sass',
                'browserify',
                'assemble',
                'imagemin:dist'
            ]
        },

        // Module: grunt-bump
        // -------------------------------------------------------------------------------------------------------------
        bump: {
            options: {
                files: ['package.json', 'bower.json', 'composer.json'],
                commit: true,
                commitMessage: 'Bump version to v%VERSION%',
                commitFiles: ['-a'],
                createTag: true,
                tagName: 'v%VERSION%',
                tagMessage: 'Version %VERSION%',
                push: true,
                pushTo: 'origin',
                gitDescribeOptions: '--tags --always --abbrev=1 --dirty=-d',
                globalReplace: false,
                prereleaseName: false,
                regExp: false
            }
        }
    });

    // Task: Serve
    // -----------------------------------------------------------------------------------------------------------------
    grunt.registerTask('serve', function (target) {
        if (target === 'dist') {
            return grunt.task.run([
                'build',
                'browserSync:dist'
            ]);
        }

        grunt.task.run([
            'clean:dev',
            'wiredep',
            'concurrent:dev',
            'postcss',
            'exorcise',
            'jshint',
            'browserSync:dev',
            'watch'
        ]);
    });

    // Redirect `server` task to `serve` task.
    grunt.registerTask('server', function (target) {
        grunt.log.warn('The `server` task has been deprecated. Use `grunt serve` to start a server.');
        grunt.task.run([target ? ('serve:' + target) : 'serve']);
    });

    // Build Tasks
    // -----------------------------------------------------------------------------------------------------------------
    grunt.registerTask('build', [
        'clean:dist',
        'wiredep',
        'jshint',
        'concurrent:dist',
        'postcss',
        'exorcise',
        'copy:dist',
        'useminPrepare',
        'concat:generated',
        'cssmin:generated',
        'uglify:generated',
        'usemin'
    ]);

    // Task: Default
    // -----------------------------------------------------------------------------------------------------------------
    grunt.registerTask('default', ['serve']);
};
