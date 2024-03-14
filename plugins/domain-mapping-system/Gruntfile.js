module.exports = function (grunt) {
    /**
     * Load required Grunt tasks. These are installed based on the versions listed
     * in `package.json` when you do `npm install` in this directory.
     */
    grunt.loadNpmTasks("grunt-contrib-sass");
    grunt.loadNpmTasks("grunt-contrib-watch");
    grunt.loadNpmTasks("grunt-postcss");
    grunt.loadNpmTasks("grunt-contrib-copy");
    grunt.loadNpmTasks("grunt-contrib-cssmin");
    grunt.loadNpmTasks("grunt-contrib-uglify");

    var userConfig = {
        publicDir: "assets",
        adminDir: "admin",
        assetsDir: "assets"
    };

    var taskConfig = {
        sass: {
            compile: {
                files: {
                    "<%= publicDir %>/css/dms.css": "<%= assetsDir %>/scss/dms.scss",
                    "<%= publicDir %>/css/dms-yoast.css": "<%= assetsDir %>/scss/dms-yoast.scss",
                }
            }
        },
        postcss: {
            options: {
                processors: [
                    require("autoprefixer")({
                        browsers: "last 5 versions"
                    })
                ]
            },
            dist: {
                files: [{
                    src: "<%= publicDir %>/css/dms.css",
                },{
                    src: "<%= publicDir %>/css/dms-yoast.css",
                }]
            }
        },
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: "<%= publicDir %>/css/",
                    src: ['*.css', '!*.min.css'],
                    dest: "<%= publicDir %>/css/",
                    ext: ".min.css"
                }]
            }
        },
        uglify: {
            target: {
                files: [],
            }
        },
        delta: {
            options: {
                livereload: false
            },

            /**
             * When the SCSS files change, we need to compile and copy to build dir
             */
            sass: {
                files: ["<%= assetsDir %>/scss/**/*.scss"],
                tasks: ["sass:compile", "cssmin", "uglify", "postcss:dist"],
                options: {
                    livereload: true
                }
            },
        }
    };

    grunt.initConfig(grunt.util._.extend(taskConfig, userConfig));

    grunt.renameTask("watch", "delta");
    grunt.registerTask("watch", [
        "sass:compile",
        "cssmin",
        // "copy:assets",
        "postcss:dist",
        "delta"
    ]);

    grunt.registerTask("build", [
        "sass:compile",
        "postcss:dist",
        "cssmin",
        "uglify",
        // "copy:assets"
    ]);

    grunt.registerTask("default", ["sass:compile", "postcss:dist"]);
};