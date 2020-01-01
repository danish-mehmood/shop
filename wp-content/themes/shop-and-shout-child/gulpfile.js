var gulp 		= require('gulp'),
	sass 		= require('gulp-sass'),
	sourcemaps 	= require('gulp-sourcemaps');

gulp.task('sass', function(done){
	return gulp.src('styles/scss/index.scss')
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('styles/css'))
	done();
});

gulp.task('watch', gulp.series('sass', function(){
	gulp.watch('styles/scss/**/*.scss', gulp.series('sass'));
}));