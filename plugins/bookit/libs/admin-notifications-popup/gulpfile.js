const gulp = require('gulp');
const sass = require('gulp-dart-sass');
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync').create();
const watch = require('gulp-watch');
const cssmin = require('gulp-cssmin');
const dependents = require('gulp-dependents');
const debug = require('gulp-debug'); // Add this

// styles only for admin pages
gulp.task( 'anp-styles', () => {
    return gulp.src('./assets/scss/anp-style.scss', { since: gulp.lastRun( 'anp-styles' ) })
        .pipe(dependents())
        .pipe(debug({title: 'dependents:'}))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(cssmin())
        .pipe(gulp.dest('./assets/css/'))
        .pipe(browserSync.stream());
});

gulp.task( 'watch', function () {
    watch('./assets/scss/anp-style.scss', gulp.series( 'anp-styles' ));
});

gulp.task( 'build', gulp.series( 'anp-styles' ));

gulp.task( 'default', gulp.parallel( gulp.series( 'anp-styles' ), 'watch' ));