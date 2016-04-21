var gulp = require('gulp'),
	autoprefixer = require('gulp-autoprefixer'),
	newer = require('gulp-newer'),
	sass = require('gulp-sass'),
	uglify = require('gulp-uglify');

var paths = {
	appSrc: 'app/**',
	appDest: 'dist',
	assetsSrc: [
		'img/**',
		'node_modules/jquery/dist/jquery.min.js',
		'node_modules/shariff/build/shariff.min.js',
		'node_modules/shariff/build/shariff.min.css',
		'node_modules/font-awesome/fonts/fontawesome-webfont.*'
	],
	assetsDest: 'dist/assets',
	scriptSrc: 'js/**/*.js',
	scriptDest: 'dist/assets',
	styleSrc: 'scss/**/*.scss',
	styleDest: 'dist/assets'
};

gulp.task('clear', function() {
	return del(paths.appDest);
});

gulp.task('app', function() {
	gulp.src(paths.appSrc, { dot: true })
		.pipe(newer(paths.appDest))
		.pipe(gulp.dest(paths.appDest));
});

gulp.task('assets', function() {
	gulp.src(paths.assetsSrc)
		.pipe(newer(paths.assetsDest))
		.pipe(gulp.dest(paths.assetsDest));
});

gulp.task('script', function() {
	gulp.src(paths.scriptSrc)
		.pipe(newer({
			dest: paths.scriptDest,
			ext: '.js'
		}))
		.pipe(uglify().on('error', console.log))
		.pipe(gulp.dest(paths.scriptDest));
});

gulp.task('style', function() {
	gulp.src(paths.styleSrc)
		.pipe(newer(paths.styleDest + '/style.css'))
		.pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
		.pipe(autoprefixer())
		.pipe(gulp.dest(paths.styleDest));
});

gulp.task('watch', ['default'], function() {
	gulp.watch(paths.appSrc, ['app']);
	gulp.watch(paths.assetsSrc, ['assets']);
	gulp.watch(paths.scriptSrc, ['script']);
	gulp.watch(paths.styleSrc, ['style']);
});

gulp.task('default', ['app', 'assets', 'script', 'style']);
