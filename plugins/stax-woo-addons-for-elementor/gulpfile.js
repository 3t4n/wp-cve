const gulp = require("gulp"),
  sass = require("gulp-sass"),
  rename = require("gulp-rename"),
  uglify = require("gulp-uglify"),
  csso = require("gulp-csso"),
  babel = require("gulp-babel"),
  postcss = require("gulp-postcss");

gulp.task("build:styles:widgets", () => {
  return gulp
    .src(["./widgets/**/*.scss"], { base: "./" })
    .pipe(sass())
    .pipe(gulp.dest("./"))
    .pipe(csso())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(gulp.dest("./"));
});

gulp.task("build:styles:main", () => {
  return gulp
    .src(["./assets/scss/**/*.scss"])
    .pipe(sass())
    .pipe(postcss())
    .pipe(gulp.dest("./assets/css/"))
    .pipe(csso())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(gulp.dest("./assets/css/"));
});

gulp.task(
  "build:styles",
  gulp.parallel("build:styles:widgets", "build:styles:main")
);

gulp.task("build:scripts:widgets", () => {
  return gulp
    .src(["./widgets/**/*.js", "!./widgets/**/*.min.js"], { base: "./" })
    .pipe(rename({ suffix: ".min" }))
    .pipe(
      babel({
        presets: ["@babel/env"],
      })
    )
    .pipe(uglify())
    .pipe(gulp.dest("./"));
});

gulp.task("build:scripts:main", () => {
  return gulp
    .src(["./assets/js/**/*.js", "!./assets/js/**/*.min.js"], { base: "./" })
    .pipe(rename({ suffix: ".min" }))
    .pipe(
      babel({
        presets: ["@babel/env"],
      })
    )
    .pipe(uglify())
    .pipe(gulp.dest("./"));
});

gulp.task(
  "build:scripts",
  gulp.parallel("build:scripts:widgets", "build:scripts:main")
);

gulp.task("watch:styles", () => {
  gulp.watch("./widgets/**/*.scss", gulp.series(["build:styles"]));
});
