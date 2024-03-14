var gulp = require("gulp");

var sass = require("gulp-sass");
var sourcemaps = require("gulp-sourcemaps");

var plumber = require("gulp-plumber");
var browserSync = require("browser-sync");

var dir = {
	"source": "./src",
	"export": "./res"
};

gulp.task("server", function(){
	browserSync({
		proxy: "wp.potato4d.me",
		files: [
			"./res/js/*.js",
			"./res/css/*.css",
			"./**/*.php",
		]
	});
});

gulp.task("sass", function(){
	gulp.src(dir.source + "/scss/*.scss")
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: "compressed"
		}))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(dir.export+"/css"));
});

gulp.task("default", ["server"], function (){
	gulp.watch([dir.source+"/scss/*.scss"], ["sass"]);
});
