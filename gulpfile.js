var config = {
    sourceDirectory : './development', 
    outputDirectory: './assets',
};

var gulp    = require( "gulp" ),
    plugins = require( "gulp-load-plugins" )(),
    plumber = require( 'gulp-plumber' ),
    babel   = require( 'gulp-babel' );

gulp.task( 'sass', function () {
    gulp.src( [ config.sourceDirectory + '/sass/main.scss', config.sourceDirectory + '/sass/vendor.scss' ] )
    .pipe( plumber() )
    .pipe( plugins.sass() )
    .pipe( plugins.autoprefixer() )
    .pipe( gulp.dest( config.outputDirectory + '/css' ) )
    .pipe( plugins.cssmin() )
    .pipe( plugins.rename( { suffix : '.min' } ) )
    .pipe( gulp.dest( './css' ) )
} );


gulp.task( 'js-main', function () {
    return gulp.src( [ "./development/js/main.js" ] )
    .pipe( plumber() )
    .pipe( plugins.include( {
        extensions   : "js",
        hardFail     : true,
        includePaths : [ __dirname + "/node_modules", __dirname + "/development/js" ]
    } ) )
    .pipe( babel( {
        presets : [ 'es2015' ]
    } ) )
    .pipe( gulp.dest( config.outputDirectory + '/js' ) );
} );

gulp.task( 'js-plugins', function () {
    return gulp.src( [ "./development/js/plugins.js" ] )
    .pipe( plumber() )

    .pipe( plugins.include( {
        extensions   : "js",
        hardFail     : true,
        includePaths : [ __dirname + "/node_modules", __dirname + "/development/js" ]
    } ) )
    .pipe( gulp.dest( config.outputDirectory + '/js' ) );
} );

gulp.task( 'watch', function () {
    gulp.watch( [ config.sourceDirectory + '/sass/_variables.scss', config.sourceDirectory + '/sass/theme/**/*.scss', config.sourceDirectory + '/sass/main.scss', config.sourceDirectory + '/sass/woocommerce/**/*.scss' ], [ 'sass' ] );
    gulp.watch( [ config.sourceDirectory + '/js/main.js', config.sourceDirectory + '/js/theme/*.js' ], [ 'js-main' ] );
    gulp.watch( [ config.sourceDirectory + '/js/plugins.js', config.sourceDirectory + '/js/vendor/**/*.js' ], [ 'js-plugins' ] );
    // Other watchers
} );


gulp.task( 'default', [ 'watch' ] );