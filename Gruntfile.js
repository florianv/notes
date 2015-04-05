module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        publicDir: 'web/bundles/notesapp',
        baseAssetsDir: 'src/Notes/Bundle/AppBundle/Resources/assets',
        faFontsDir: '<%= baseAssetsDir %>/components/font-awesome/fonts',
        coffeeDir: '<%= baseAssetsDir %>/coffee',
        sassDir: '<%= baseAssetsDir %>/scss',
        cssDir: '<%= publicDir %>/css',
        jsDir: '<%= publicDir %>/js',
        fontsDir: '<%= publicDir %>/fonts',
        componentsDir: '<%= baseAssetsDir %>/components',

        // Cleans the public assets
        clean: {
            build: {
                src: ['<%= publicDir %>/**']
            }
        },

        // Copies files
        copy: {
            templates: {
                files: [
                    {
                        expand: true,
                        cwd: '<%= coffeeDir %>',
                        src: ['**/*.html'],
                        dest: '<%= jsDir %>'
                    }
                ]
            },
            fonts: {
                files: [
                    {
                        expand: true,
                        src: '<%= faFontsDir %>/**',
                        dest: '<%= fontsDir %>',
                        flatten: true,
                        filter: 'isFile'
                    }
                ]
            },
            js: {
                files: [
                    {
                        src: ['<%= componentsDir %>/jquery/dist/jquery.js'],
                        dest: '<%= jsDir %>/vendor/jquery.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone/backbone.js'],
                        dest: '<%= jsDir %>/vendor/backbone.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.babysitter/lib/backbone.babysitter.js'],
                        dest: '<%= jsDir %>/vendor/babysitter.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.marionette/lib/core/amd/backbone.marionette.js'],
                        dest: '<%= jsDir %>/vendor/marionette.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.wreqr/lib/backbone.wreqr.js'],
                        dest: '<%= jsDir %>/vendor/wreqr.js'
                    },
                    {
                        src: ['<%= componentsDir %>/requirejs/require.js'],
                        dest: '<%= jsDir %>/vendor/require.js'
                    },
                    {
                        src: ['<%= componentsDir %>/text/text.js'],
                        dest: '<%= jsDir %>/vendor/text.js'
                    },
                    {
                        src: ['<%= componentsDir %>/underscore/underscore.js'],
                        dest: '<%= jsDir %>/vendor/underscore.js'
                    },
                    {
                        src: ['<%= componentsDir %>/handlebars/handlebars.amd.js'],
                        dest: '<%= jsDir %>/vendor/handlebars.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone.syphon/lib/backbone.syphon.js'],
                        dest: '<%= jsDir %>/vendor/syphon.js'
                    },
                    {
                        src: ['<%= componentsDir %>/bootstrap-sass/assets/javascripts/bootstrap.js'],
                        dest: '<%= jsDir %>/vendor/bootstrap.js'
                    },
                    {
                        src: ['<%= componentsDir %>/jquery.cookie/jquery.cookie.js'],
                        dest: '<%= jsDir %>/vendor/jquery.cookie.js'
                    },
                    {
                        src: ['<%= componentsDir %>/spin.js/spin.js'],
                        dest: '<%= jsDir %>/vendor/spin.js'
                    },
                    {
                        src: ['<%= componentsDir %>/spin.js/jquery.spin.js'],
                        dest: '<%= jsDir %>/vendor/jquery.spin.js'
                    },
                    {
                        src: ['<%= componentsDir %>/moment/moment.js'],
                        dest: '<%= jsDir %>/vendor/moment.js'
                    },
                    {
                        src: ['<%= componentsDir %>/backbone-query-parameters/backbone.queryparams.js'],
                        dest: '<%= jsDir %>/vendor/backbone.queryparams.js'
                    }
                ]
            }
        },

        // Compiles the sass files into the public js
        compass: {
            dev: {
                options: {
                    sassDir: '<%= sassDir %>',
                    cssDir: '<%= cssDir %>',
                    environment: 'development'
                }
            },
            prod: {
                options: {
                    sassDir: '<%= sassDir %>',
                    cssDir: '<%= cssDir %>',
                    environment: 'production',
                    outputStyle: 'compressed'
                }
            }
        },

        // Compiles coffee files
        coffee: {
            main: {
                expand: true,
                cwd: '<%= coffeeDir %>',
                src: ['**/*.coffee'],
                dest: '<%= jsDir %>',
                ext: '.js'
            }
        },

        // Lints the coffee files
        coffeelint: {
            main: {
                files: [
                    {
                        expand: true,
                        src: ['<%= coffeeDir %>/**/*.coffee']
                    }
                ]
            }
        },

        // Uglifies compiled js files
        uglify: {
            main: {
                files: [
                    {
                        expand: true,
                        cwd: '<%= jsDir %>',
                        src: '**/*.js',
                        dest: '<%= jsDir %>'
                    }
                ]
            }
        },

        watch: {
            sass: {
                files: ['<%= sassDir %>/**'],
                tasks: ['sass:watch'],
                options: {
                    spawn: false
                }
            },
            coffee: {
                files: ['<%= coffeeDir %>/**'],
                tasks: ['coffee:watch', 'templates:watch'],
                options: {
                    spawn: false
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-coffee');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-coffeelint');

    // Task for watching SASS file changes
    grunt.registerTask('sass:watch', ['compass:dev']);

    // Task for watching Coffee file changes
    grunt.registerTask('coffee:watch', ['coffee']);

    // Task for watching templates changes
    grunt.registerTask('templates:watch', ['copy:templates']);

    // Task to run when deploying
    grunt.registerTask('dev', ['clean', 'copy:templates', 'copy:fonts', 'copy:js', 'compass:prod', 'coffee']);

    // Task to run when deploying
    grunt.registerTask('prod', ['clean', 'copy:templates', 'copy:fonts', 'copy:js', 'compass:prod', 'coffee', 'uglify']);

    // Default task
    grunt.registerTask('default', ['prod']);
};
