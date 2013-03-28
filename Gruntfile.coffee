module.exports = (grunt) ->
  
   # Project configuration.
   grunt.initConfig
   
      less:
         compile:
            options:
               paths: [
                  "design/less",
                  "components/bootstrap/less",
               ]
            files:
               "design/api.css": "design/less/api.less"

      watch:
         less:
            files: "design/less/**/*.less",
            tasks: "less"

   # Load modules.
   grunt.loadNpmTasks "grunt-contrib-less"
   grunt.loadNpmTasks "grunt-contrib-watch"

   grunt.registerTask "compile", ["less"]