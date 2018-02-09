/*-----------------------------------*\
  #Gulpfile: Automating tasks
\*-----------------------------------*/


/**
 * LOAD PLUGINS
 */
var gulp          = require('gulp')
    ,bower        = require('gulp-bower')
    ,merge        = require('merge-stream')
    ,del          = require('del')
    ,rs           = require('run-sequence')
    ,notify       = require('gulp-notify')
    ,$            = require('gulp-load-plugins')();

var config = {
  stylesPath            : './source/assets/src/scss'
  ,imagesPath           : './source/assets/img'
  ,scriptsPath          : './source/assets/src/js'
  ,appSourcePath        : './source/assets/src/js/app'
  ,directivesSourcePath : './source/assets/src/js/directives'
  ,templatePath         : './source/templates'
  ,modelsPath           : './source/models'
  ,viewsPath            : './source/views'
  ,controllersPath      : './source/controllers'
  ,vendorPath           : './source/assets/vendor'
  ,acfPath              : './source/acf'
  ,buildPath            : '../web/wp-content/themes/apastron'
}

/**
 * COMPILE VENDOR FILES
 */
gulp.task('vendor', function() {

  /**
   * COMPILE FONTS VENDOR FILES
   */
  var fonts = gulp.src([
    config.vendorPath  + '/fonts/**.*'
  ]) 
    .pipe(gulp.dest(config.buildPath + '/assets/vendor/fonts'))
    .pipe($.size({title: 'Vendor: Fonts'}));

  var fontAwesome = gulp.src([
        config.vendorPath  + '/font-awesome/**/*.*'
      ])
      .pipe(gulp.dest(config.buildPath + '/assets/vendor/font-awesome'))
      .pipe($.size({title: 'Vendor: Font Awesome'}));

  /**
   * COMPILE JS VENDOR FILES
   */
  var libs = gulp.src([
    ,config.vendorPath + '/angular/angular.js'
    ,config.vendorPath + '/angular-route/angular-route.js'
    ,config.vendorPath + '/angular-animate/angular-animate.js'
    ,config.vendorPath + '/angular-cookies/angular-cookies.js'
    ,config.vendorPath + '/angular-messages/angular-messages.js'
    ,config.vendorPath + '/angular-cookies/angular-cookies.js'
  ])
    .pipe($.concat('vendor.js'))
    .pipe($.uglify())
    .pipe(gulp.dest(config.buildPath + '/assets/vendor/js'))
    .pipe($.size({title: 'Vendor: JS'}));

  return merge(fonts, fontAwesome, libs);
});

/**
 * COMPILE VENDOR FILES
 */
gulp.task('app', function() {

  /**
   * COMPILE JS APP DIRECTIVE FILES
   */
  var app = gulp.src([
    config.scriptsPath  + "/app/apastron.js"
    ,config.scriptsPath  + "/directives/*"
  ])
    //.pipe($.uglify())
    .pipe(gulp.dest(config.buildPath + '/assets/js/app'))
    .pipe($.size({title: 'App: JS'}));

  return merge(app);
});

/**
 * COMPILE SCRIPTS
 */
gulp.task('scripts', function() {
  return gulp.src([
      config.scriptsPath  + "/siema.js"
    ])
    .pipe($.sourcemaps.init())
    .pipe($.concat('scripts.js'))
    //.pipe($.uglify())
    .pipe(gulp.dest(config.buildPath + '/assets/js'))
    .pipe($.sourcemaps.write('./maps/'))
    .pipe(gulp.dest(config.buildPath))
    .pipe($.size({title: 'Scripts'}));
});

/**
 * COMPILE STYLE
 */
gulp.task('styles', function() {
  var style = gulp.src(config.stylesPath  + '/app.scss')
    .pipe($.sourcemaps.init())
      .pipe($.sass({outputStyle:'nested'})
        .on('error', $.notify.onError(function (error) {
            return 'Error: ' + error.message;})))
      .pipe($.autoprefixer({
       browsers: [
         'last 2 version'
         ,'> 1%'
         ,'safari 5'
         ,'ie 8'
         ,'ie 9'
         ,'opera 12.1'
         ,'ios 6'
         ,'android 4'
       ],
       cascade: false
      }))
      .pipe($.rename({basename: 'style'}))
      .pipe($.cssmin()
        .on('error', $.notify.onError(function (error) {
            return 'Error: ' + error.message;})))
      .pipe($.sourcemaps.write('./maps/'))
      .pipe(gulp.dest(config.buildPath))
    .pipe($.size({title:'Styles'}));


  return merge(style);
});

/**
 * BREAK COMPILED CSS TO MULTIPLE FILES FOR IE
 */
var bless = require('gulp-bless');
gulp.task('iebless', function() {
    gulp.src(config.buildPath + '/style.css')
      .pipe(bless({
        suffix: '-part'
      }))
      .pipe($.cssmin()
        .on('error', $.notify.onError(function (error) {
          return 'Error: ' + error.message;})))
      .pipe(gulp.dest(config.buildPath + '/assets/ieCSS'));
});


/**
 * PRETTIFY THEME FILES
 */
gulp.task('theme', function() {
  var template = gulp.src(config.templatePath + '/**/*.php')
    .pipe($.prettify({
      indent_inner_html: true,
      indent_size: 2
    }))
    .pipe(gulp.dest(config.buildPath))
    .pipe($.size({title: 'Template'}));

  var m = gulp.src(config.modelsPath + '/**/*.*')
    .pipe($.prettify({
      indent_inner_html: true,
      indent_size: 2
    }))
    .pipe(gulp.dest(config.buildPath + '/models'))
    .pipe($.size({title: 'Models'}));

  var b = gulp.src(config.businesslogicPath + '/**/*.*')
    .pipe($.prettify({
      indent_inner_html: true,
      indent_size: 2
    }))
    .pipe(gulp.dest(config.buildPath + '/business-logic'))
    .pipe($.size({title: 'Business Logic'}));


  var v = gulp.src(config.viewsPath + '/**/*.*')
    // .pipe($.prettify({
    //   indent_inner_html: true,
    //   indent_size: 2
    // }))
    .pipe(gulp.dest(config.buildPath + '/views'))
    .pipe($.size({title: 'Views'}));

  var c = gulp.src(config.controllersPath + '/**/*.*')
    .pipe($.prettify({
      indent_inner_html: true,
      indent_size: 2
    }))
    .pipe(gulp.dest(config.buildPath + '/controllers'))
    .pipe($.size({title: 'Controllers'}));

  return merge(template, b, m, v, c);
});

/**
 * COMPRESS IMAGES AND CACHE THEM
 */
gulp.task('images', function() {
  var imgs = gulp.src(config.imagesPath + '/**/*')
    .pipe(
      $.imagemin({
        optimizationLevel: 5,
        progressive: true,
        interlaced: true
      })
    )
    .pipe(gulp.dest(config.buildPath + '/assets/img'))
    .pipe($.size({title: 'Images'}));

  var ss = gulp.src(config.imagesPath + '/favicon.png')
    .pipe(gulp.dest(config.buildPath));
  return merge(imgs, ss);
});


/**
 * CSS LINT
 */
gulp.task('csslint', function() {
  return gulp.src(config.buildPath + '/style.css')
    .pipe($.csslint())
    .pipe($.csslint.reporter()
  );
});

/**
 * JSHINT
 */
gulp.task('jshint', function() {
  return gulp.src(config.scriptsPath + '/*.js')
    .pipe($.jshint())
    .pipe($.jshint.reporter()
  );
});

/**
 * WATCHING FOR CHANGES
 */
gulp.task('watch', function() {
  gulp.watch([
    config.templatePath     + '/**/*'
    ,config.modelsPath      + '/**/*'
    ,config.viewsPath       + '/**/*'
    ,config.controllersPath + '/**/*'
  ], ['theme']);

  gulp.watch(config.stylesPath   + '/**/*', ['styles']);
  gulp.watch(config.scriptsPath  + '/**/*', ['scripts', 'app']);
  gulp.watch(config.imagesPath   + '/**/*', ['images']);
  gulp.watch(config.buildPath    + '/style.css', ['iebless']).on('change', function () {
        notify("Changes updated").write('');
    });;

});

/**
 * BUILD TASK
 */
gulp.task('build', function(cb) {
  rs(
    'clean'
    ,'bower'
    ,'vendor'
    ,'app'
    ,'scripts'
    ,'styles'
    ,'iebless'
    ,'theme'
    ,'images'
    ,cb
  );
});

/**
 * CLEAR CACHE
 */
gulp.task('clean', function(cb) {
  return del(
    [
      config.buildPath  + '/**/*'
      ,config.buildPath + '/.*'
    ]
    ,{force: true}
    ,cb
  );
});

/**
 * BOWER
 */
gulp.task('bower', function() {
  return bower(config.vendorPath)
    .pipe($.size({title: 'Bower'}))
});


/**
 * DEFAULT TASK
 */
gulp.task('default', function(cb) {
  rs(
    'build'
    ,'watch'
    ,cb
  );
});
