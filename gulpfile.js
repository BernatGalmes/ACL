// including plugins
var gulp = require('gulp')
    , minifyCss = require("gulp-minify-css"),
    rename = require("gulp-rename"),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    debug = require('gulp-debug');

var APP_PREFIX = 'acl';
var cssDest = './public_html/acl/recursos/css';
var folder_raw_css_libs = './assets/css/libraries/';
var folder_raw_css_custom = './assets/css/custom/';
var cssInFiles = {
    'main': [
        folder_raw_css_libs + 'bootstrap.css',
        folder_raw_css_libs + 'bootstrap-notifications.css',
        folder_raw_css_custom + 'phpacl.css',
        folder_raw_css_libs + 'font-pacifico.css',
        folder_raw_css_libs + 'font-awesome.css'
    ],
    'tables': [
        folder_raw_css_libs + 'bootstrap.css',
        folder_raw_css_libs + 'dataTables.bootstrap.css',
        folder_raw_css_libs + 'fixedHeader.bootstrap.css',
        folder_raw_css_libs + 'responsive.bootstrap.css',
        folder_raw_css_libs + 'bootstrap-notifications.css',
        folder_raw_css_custom + 'phpacl.css',
        folder_raw_css_libs + 'font-pacifico.css',
        folder_raw_css_libs + 'font-awesome.css'
    ]
};

var jsDest = './public_html/acl/recursos/js';
var folder_raw_js_libs = './assets/js/libraries/';
var folder_raw_js_custom = './assets/js/custom/';

var jsInFiles = {
    'main': [
        folder_raw_js_libs + 'jquery-3.2.1.js',
        folder_raw_js_libs + 'bootstrap.js',
        folder_raw_js_custom + 'acl.js'
    ],
    'tables': [
        folder_raw_js_libs + 'jquery-3.2.1.js',
        folder_raw_js_libs + 'bootstrap.js',
        folder_raw_js_libs + 'jquery.dataTables.js',
        folder_raw_js_libs + 'dataTables.bootstrap.min.js',
        folder_raw_js_libs + 'dataTables.fixedHeader.js',
        folder_raw_js_libs + 'dataTables.responsive.js',
        folder_raw_js_libs + 'responsive.bootstrap.js',
        folder_raw_js_custom + 'acl.js'
    ],
    "system":[
        folder_raw_js_libs + 'jquery-3.2.1.js',
        folder_raw_js_libs + 'bootstrap.js',
        folder_raw_js_libs + 'jquery.dataTables.js',
        folder_raw_js_libs + 'dataTables.bootstrap.min.js',
        folder_raw_js_libs + 'dataTables.fixedHeader.js',
        folder_raw_js_libs + 'dataTables.responsive.js',
        folder_raw_js_libs + 'responsive.bootstrap.js',
        folder_raw_js_custom + 'acl.js',
        folder_raw_js_custom + 'acl-system.js'
    ]
};

// task
gulp.task('styles', function () {
    for (var module in cssInFiles) {
        gulp.src(cssInFiles[module])
            .pipe(debug({title: 'verbose:', minimal: false}))
            .pipe(concat(APP_PREFIX + '-' + module + '.css'))
            .pipe(minifyCss())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(cssDest));
    }
});

gulp.task('scripts', function() {
    for(var module in jsInFiles){
        gulp.src(jsInFiles[module])
            .pipe(concat(APP_PREFIX + '-' + module + '.js'))
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(uglify())
            .pipe(gulp.dest(jsDest));
    }
});
