var gulp = require('gulp');
var sass = require('gulp-sass');
var watch = require('gulp-watch');
var sourcemaps = require('gulp-sourcemaps');
var browserify = require('browserify');
var rename = require('gulp-rename');
var source = require('vinyl-source-stream'); // required to dest() for browserify
var browserSync = require('browser-sync').create();
var concat = require('gulp-concat');
var notifier = require('node-notifier');
var jshint = require('gulp-jshint');
var stylish = require('jshint-stylish');
var babel = require('gulp-babel');

gulp.task('sass', function () {
	return gulp.src('./assets/sass/style.scss')
	.pipe(sourcemaps.init())
	.pipe(sass().on('error', sass.logError)) // .on('error', sass.logError) prevents gulp from crashing when saving a typo or syntax error
	.pipe(sourcemaps.write())
	.pipe(gulp.dest('./'))
	.pipe(browserSync.stream()); // causes injection of styles on save
});

gulp.task('sync', ['sass'], function() {
	browserSync.init({
		open: true,
		proxy: "http://hammerheadsilver.local"
	});
});

var vendors = {
	merge: [
		//'path-to-file.js'
	]
};

gulp.task('vendors', function() {
	return gulp.src(vendors.merge)
		.pipe(concat('vendors.js'))
		.pipe(gulp.dest('./assets/vendors/js/'));
});

gulp.task('javascript', function() {
		
	var bundleStream = browserify('./assets/js/main.js')
		.transform("babelify", {presets: ["@babel/preset-env"]})
		.bundle()
		.on('error', function(err) {
			console.log(err.stack);
			notifier.notify({
				'title': 'Browserify Compilation Error',
				'message': err.message
			});
			this.emit('end');
		});

	return bundleStream
		.pipe(source('main.js'))
		.pipe(rename('bundle.js'))
		.pipe(gulp.dest('./assets/js/'))
		.pipe(browserSync.stream());
});

gulp.task('validateJS', function() {
	return gulp.src(['./assets/js/**/*.js', '!./assets/js/bundle.js'])
		.pipe(jshint())
		.pipe(jshint.reporter(stylish));
});

gulp.task('watch', function() {
	gulp.watch('./assets/sass/**/*.scss', ['sass']);
	gulp.watch(['./assets/js/**/*.js', '!./assets/js/bundle.js'], ['javascript']);
	gulp.watch(['./assets/vendors/js/*.js', '!./assets/vendors/js/vendors.min.js'], ['vendors']);
});

// Default Task
gulp.task('default', ['vendors', 'javascript', 'sass', 'watch', 'sync']);