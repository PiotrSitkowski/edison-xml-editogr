'use strict';
const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const cssnano = require('gulp-cssnano');
const sourcemaps = require('gulp-sourcemaps');
const useref = require('gulp-useref');
const clean = require('gulp-clean');
const gulpsync = require('gulp-sync')(gulp);
const gulpif = require('gulp-if');
const uglify = require('gulp-uglify');
const minifyCss = require('gulp-clean-css');
const stripCssComments = require('gulp-strip-css-comments');
const browserify  = require('browserify');
const rev = require('gulp-rev');
const revReplace = require('gulp-rev-replace');

gulp.task('clear-assets-css', () =>
    gulp.src('./assets/css/', {read: false})
        .pipe(clean())
);

gulp.task('clear-pub-css', () =>
    gulp.src('./public_html/css/', {read: false})
        .pipe(clean())
);

gulp.task('clear-tpl', () =>
    gulp.src('templates/', {read: false})
        .pipe(clean())
);

gulp.task('useref',() =>
  gulp.src('assets/**/*.tpl')
    .pipe(useref({ searchPath: 'assets' }))
    .pipe(gulpif('*.js', uglify({
  mangle: false,
  compress: true,
  output: { beautify: false }
 })))
    .pipe(gulpif('*.css', minifyCss()))
    .pipe(gulpif('*.css', stripCssComments()))
    .pipe(gulpif('*.js', rev()))
    .pipe(gulpif('*.css', rev()))
    .pipe(revReplace({replaceInExtensions: ['.tpl']}))
    .pipe(gulp.dest('build'))
);

gulp.task('sass', () =>
gulp.src(['./assets/sass/**/*.sass', './assets/sass/**/*.scss'])
    .pipe(sass({ errLogToConsole: true }).on('error', sass.logError))
    .pipe(sourcemaps.init())
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./assets/css'))
);


gulp.task('copy-tpl', () =>
         gulp.src(['build/templates/**'])
          .pipe(gulp.dest('templates/'))
);
gulp.task('copy-css', () =>
         gulp.src(['build/css/**'])
          .pipe(gulp.dest('./public_html/css/'))
);

gulp.task('clear-build', () =>
    gulp.src('build/', {read: false})
        .pipe(clean())
);


gulp.task('build', gulpsync.sync(['clear-assets-css', 'clear-pub-css', 'clear-tpl', 'sass', 'useref', 'copy-tpl', 'copy-css', 'clear-build', 'clear-assets-css']));
