module.exports = (grunt) ->
  
    # Project configuration.
    grunt.initConfig
    
        stylus:
            compile:
                options:
                    paths: [
                        "components/topcoat/src/style",
                        "design/stylus",
                    ]
                    urlfunc: "url"
                files:
                    "design/api.css": "design/stylus/api.styl"

        less:
            compile:
                options:
                    paths: [
                        "components/bootstrap/less",
                        "design/less",
                    ]
                files:
                    "design/api.css": "design/less/api.less"

        watch:
            less:
                files: "design/less/**/*.less",
                tasks: "less"

    # Load modules.
    grunt.loadNpmTasks "grunt-contrib-less"
    grunt.loadNpmTasks "grunt-contrib-stylus"
    grunt.loadNpmTasks "grunt-contrib-watch"