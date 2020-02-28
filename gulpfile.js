var gulp = require("gulp");
var path = require('path');
var sourcemaps = require("gulp-sourcemaps");
var babel = require("gulp-babel");
var uglify = require('gulp-uglify');
var less = require('gulp-less');
var rename = require('gulp-rename');
var path = require('path');

gulp.task("js", function () {
  return gulp.src([ path.join("./js/src/*.js") ])
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest("./js/"));
});

gulp.task("jsmin", function () {
  return gulp.src([ path.join("./js/src/*.js") ])
    .pipe(sourcemaps.init())
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename({extname: '.min.js'}))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest("./js/"));
});

gulp.task("css", function () {
  return gulp.src([ path.join("./css/src/**/*.less") ])
    .pipe(sourcemaps.init())
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest("./css/"));
});

gulp.task('watch', function () {
  gulp.watch(['./css/src/**/*.less'], gulp.parallel('css'));
  gulp.watch([ './js/src/*.js' ], gulp.parallel('jsmin'));
});

gulp.task('build', gulp.parallel('css', 'jsmin'));