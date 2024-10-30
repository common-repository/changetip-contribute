var gulp = require('gulp');
var less = require('gulp-less');

gulp.task('less', function() {
    return gulp.src('./public/less/*.less')
        .pipe(less())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('build', ['less']);

gulp.task('watch', function() {
    return gulp.watch(['./public/less/**/*.less'], ['less']);
});

gulp.task('default', ['build', 'watch']);
