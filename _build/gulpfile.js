'use strict';

const gulp = require('gulp'),
    sass = require('gulp-sass'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    cssmin = require('gulp-clean-css'),
    chmod = require('gulp-chmod');
const build = './assets/';
const assets = '../../../assets/components/app/';

gulp.task('default', ['css', 'js']);

gulp.task('watch', function () {
    gulp.watch([build + 'scss/*.scss', build + 'scss/**/*.scss'], ['css']);
    gulp.watch([build + 'js/*.js', build + 'js/app/*.js'], ['js']);
});

gulp.task('css', function () {
    var src = build + 'scss/*.scss';
    var dst = assets + 'css/web/';
    gulp.src(src)
        .pipe(sass().on('error', sass.logError))
        .pipe(cssmin())
        .pipe(gulp.dest(dst));
});

gulp.task('js', function () {
    var src = build + 'js/*.js';
    var dst = assets + 'js/web/';
    gulp.src(src)
        .pipe(uglify().on('error', function (e) {
            console.log(e);
        }))
        .pipe(gulp.dest(dst));

    src = build + 'js/app/*.js';
    dst = assets + 'js/web/app/';
    gulp.src(src)
        .pipe(uglify().on('error', function (e) {
            console.log(e);
        }))
        .pipe(gulp.dest(dst));
});

gulp.task('copy', function () {
    var src = [
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/requirejs/require.js',
        './node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        '../../../assets/components/pdotools/js/pdopage.js',
    ];
    var dst = assets + 'js/web/lib/';

    var i = 0;
    gulp.src(src)
        .pipe(uglify().on('error', function (e) {
            console.log(e);
        }))
        .pipe(chmod({
            owner: {read: true, write: true, execute: false},
            group: {read: true, write: false, execute: false},
            others: {read: true, write: false, execute: false}
        }))
        .pipe(rename(function (path) {
            path.extname = '.min.js';
            path.basename = path.basename.replace(/([-.])min/, '').replace(/\.bundle/, '').toLowerCase();
            console.log(path.basename);
        }))
        .pipe(gulp.dest(dst));

    // Fonts
    gulp.src('./node_modules/font-awesome/fonts/**').pipe(gulp.dest(assets + 'fonts/'));
});