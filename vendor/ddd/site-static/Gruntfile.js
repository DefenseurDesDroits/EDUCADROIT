'use strict';

module.exports = function (grunt) {

    // Load librairies
    // --------------------------------------------------
    require('time-grunt')(grunt);
    require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

    var appConfig = {
        src: 'src',
        dist: 'dist',
        tmp: 'tmp'
    };

    // Init config
    // --------------------------------------------------
    grunt.initConfig({
        app: appConfig,
        pkg: grunt.file.readJSON('package.json'),
        bower: grunt.file.readJSON('bower.json'),
        theme: grunt.file.readYAML('src/theme.yml'),

        // Task: grunt-contrib-clean
        // ------------------------------------------------
        clean: {
            dev: ['<%= app.tmp %>'],
            dist: ['<%= app.tmp %>', '<%= app.dist %>']
        },

        // Task: assemble
        // ------------------------------------------------
        assemble: {
            options: {
                layoutdir: '<%= app.src %>/templates/layouts',
                layout: 'default.hbs',
                partials: '<%= app.src %>/templates/partials/*.hbs',
                data: '<%= app.src %>/*.{json,yml}'
            },
            dist: {
                files: [{
                    expand: true,
                    flatten: true,
                    cwd: '<%= app.src %>/templates',
                    src: '*.hbs',
                    dest: '<%= app.tmp %>'
                }]
            }
        },

        // Task: grunt-contrib-less
        // ------------------------------------------------
        less: {
            compileTheme: {
                options: {
                    strictMath: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapURL: 'theme.css.map',
                    sourceMapFilename: '<%= app.tmp %>/styles/theme.css.map'
                },
                src: '<%= app.src %>/styles/theme.less',
                dest: '<%= app.tmp %>/styles/theme.css'
            }
        },

        // Task: grunt-autoprefixer
        // ------------------------------------------------
        autoprefixer: {
            options: {
                browsers: '<%= theme.browsers %>'
            },
            dev: {
                options: {
                    map: true
                },
                src: '<%= app.tmp %>/styles/theme.css'
            }
        },

        // Task: grunt-contrib-imagemin
        // ------------------------------------------------
        imagemin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= app.src %>',
                    src: ['{,*/}*.{gif,jpeg,jpg,png}'],
                    dest: '<%= app.dist %>'
                }]
            }
        },

        // Task: grunt-browser-sync
        // ------------------------------------------------
        browserSync: {
            dev: {
                bsFiles: {
                    src: [
                        '<%= app.tmp %>/styles/{,*}*.css',
                        '<%= app.src %>/scripts/{,*/}*.js',
                        '<%= app.src %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}',
                        '<%= app.tmp %>/*.html'
                    ]
                },
                options: {
                    notify: false,
                    watchTask: true,
                    server: {
                        baseDir: [
                            '<%= app.tmp %>',
                            '<%= app.src %>'
                        ]
                    }
                }
            },
            dist: {
                options: {
                    server: {
                        baseDir: '<%= app.dist %>'
                    }
                }
            }
        },

        // Task: grunt-contrib-watch
        // ------------------------------------------------
        watch: {
            config: {
                files: ['Gruntfile.js'],
                options: {
                    reload: true
                }
            },
            assemble: {
                files: ['<%= app.src %>/templates/{,*/}*.hbs'],
                tasks: ['assemble']
            },
            less: {
                files: ['<%= app.src %>/styles/{,*/}*.less'],
                tasks: ['less', 'autoprefixer']
            }
        },

        // Task: grunt-contrib-copy
        // ------------------------------------------------
        copy: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= app.src %>',
                    src: ['fonts/{,*/}*.{eot,ttf,woff,woff2,svg}'],
                    dest: '<%= app.dist %>'
                },{
                    expand: true,
                    cwd: '<%= app.src %>',
                    src: ['images/{,*/}*.ico'],
                    dest: '<%= app.dist %>'
                },{
                    expand: true,
                    cwd: '<%= app.tmp %>',
                    src: ['*.html'],
                    dest: '<%= app.dist %>'
                }]
            }
        },

        // Task: grunt-usemin
        // ------------------------------------------------
        useminPrepare: {
            html: '<%= app.dist %>/index.html',
            options: {
                staging: '<%= app.tmp %>'
            }
        },

        usemin: {
            html: '<%= app.dist %>/*.html'
        },

        // Task: grunt-concurrent
        // ------------------------------------------------
        concurrent: {
            dev: [
                'less',
                'assemble'
            ],
            dist: [
                'less',
                'assemble',
                'imagemin:dist'
            ]
        },

        // Task: grunt-bump
        // ------------------------------------------------
        bump: {
            options: {
                files: ['bower.json'],
                commit: true,
                commitMessage: '[RELEASE] Bump version to v%VERSION%',
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

    // Load NPM Tasks
    // --------------------------------------------------
    grunt.loadNpmTasks('assemble');

    // Task: Serve
    // --------------------------------------------------
    grunt.registerTask('serve', function(target) {
        if (target === 'dist') {
            return grunt.task.run([
                'build',
                'browserSync:dist'
            ]);
        }

        grunt.task.run([
            'clean:dev',
            'concurrent:dev',
            'autoprefixer',
            'browserSync:dev',
            'watch'
        ]);
    });

    // Task: Test
    // --------------------------------------------------
    grunt.registerTask('test', [
        'build',
        'browserSync:dist'
    ]);

    // Task: Build
    // --------------------------------------------------
    grunt.registerTask('build', [
        'clean:dist',
        'concurrent:dist',
        'autoprefixer',
        'copy:dist',
        'useminPrepare',
        'concat:generated',
        'cssmin:generated',
        'uglify:generated',
        'usemin'
    ]);

    // Task: Default
    // --------------------------------------------------
    grunt.registerTask('default', ['serve']);
};
