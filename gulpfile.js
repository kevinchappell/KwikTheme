'use strict';

var gulp = require('gulp'),
	uglify = require('gulp-uglify'),
	autoprefixer = require('gulp-autoprefixer'),
	sass = require('gulp-sass'),
	cssmin = require('gulp-cssmin'),
	concat = require('gulp-concat'),
	bsync = require('browser-sync'),
	clean = require('gulp-clean'),
	reload = bsync.reload,
	streamqueue = require('streamqueue'),
	argv = require('yargs').argv,
	gulpif = require('gulp-if'),
	rename = require('gulp-rename'),
	gzip = require('gulp-gzip'),
	header = require('gulp-header'),
	pkg = require('./package.json');

var vendorFiles = [
	'bower_components/html5shiv/dist/html5shiv.js',
	'bower_components/respond/dest/respond.src.js',
	'bower_components/jquery/dist/jquery.js',
	'bower_components/jquery-cycle2/build/jquery.cycle2.js'
];

var sourcFiles = [
	'src/js/*.js'
];

gulp.task('watch', function() {
	gulp.watch(['./src/js/*.js'], ['js']).on('change', reload);
	gulp.watch(['./src/sass/*.scss'], ['css']).on('change', reload);
});

gulp.task('css', function() {

	var banner = [
		'/*',
		'Theme Name: <%= pkg.name %>',
		'Theme URI: <%= pkg.homepage %>',
		'Author: <%= pkg.author.name %>',
		'Author URI: <%= pkg.author.url %>',
		'Version: <%= pkg.version %>',
		'Description: <%= pkg.description %>',
		'License: <%= pkg.license %>',
		'Tags: <%= pkg.tags %>',
		'Text Domain: <%= pkg.textDomain %>',
		'*/'
	].join('\n');

	gulp.src('src/sass/style.scss')
		.pipe(sass())
		.pipe(autoprefixer({
			browsers: ['last 3 versions'],
			cascade: true
		}))
		.pipe(concat('style.css'))
		.pipe(header(banner, {
			pkg: pkg
		}))
		.pipe(gulpif(argv.production, cssmin()))
		//.pipe(gulpif(argv.production, gzip()))
		.pipe(gulp.dest('./'))
		.pipe(reload({
			stream: true
		}));

	gulp.src('src/sass/admin/admin.scss')
		.pipe(sass())
		.pipe(autoprefixer({
			browsers: ['last 3 versions'],
			cascade: true
		}))
		.pipe(gulpif(argv.production, cssmin()))
		.pipe(gulp.dest('./style/'))
		.pipe(reload({
			stream: true
		}));

	return gulp.src('src/sass/admin/editor-style.scss')
		.pipe(sass())
		.pipe(autoprefixer({
			browsers: ['last 3 versions'],
			cascade: true
		}))
		.pipe(gulpif(argv.production, cssmin()))
		.pipe(gulp.dest('./style/'))
		.pipe(reload({
			stream: true
		}));

});


gulp.task('js', function() {

	// Front End JS
	return streamqueue({
				objectMode: true
			},
			gulp.src(vendorFiles),
			gulp.src(sourcFiles)
		)
		.pipe(concat('site.js'))
		// compress if production
		.pipe(gulpif(argv.production, uglify()))
		//.pipe(gulpif(argv.production, gzip({
		//	append: false
		//})))
		.pipe(gulp.dest('./js/'));

});

gulp.task('serve', function() {
	bsync({
		server: {
			baseDir: "./public"
		}
	});
});

gulp.task('clean', function() {
	return gulp.src('./public', {
		read: false
	}).pipe(clean({
		force: true
	}));
});


gulp.task('default', ['js', 'css', 'watch']);
