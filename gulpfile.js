var gulp       = require( "gulp" ),
    plugins    = require( "gulp-load-plugins" )(),
    plumber    = require( 'gulp-plumber' ),
    babel      = require( 'gulp-babel' ),
    del        = require( 'del' ),
    runSequence = require('run-sequence' ),
    config = {
        name              : 'boilerpress',
        sourceDirectory   : './development',
        outputDirectory   : './assets',
        buildDirectory    : './dist/',
        buildIncludeFiles : [
            // include common file types
            '**/*.php',
            '**/*.scss',
            '**/*.css',
            '**/*.js',
            '**/*.svg',
            '**/*.ttf',
            '**/*.otf',
            '**/*.eot',
            '**/*.woff',
            '**/*.woff2',

            // include specific files and folders
            'screenshot.png',
            'README.md',

            // exclude files and folders
            '!node_modules/**/*',
            '!dist/**/*'
        ]
    };

gulp.task( 'sass', function () {
    gulp.src( [
        config.sourceDirectory + '/sass/main.scss',
        config.sourceDirectory + '/sass/font-awesome.scss',
        config.sourceDirectory + '/sass/bootstrap.scss'
    ] )
    .pipe( plumber() )
    .pipe( plugins.sass() )
    .pipe( plugins.autoprefixer() )
    .pipe( gulp.dest( config.outputDirectory + '/css' ) )
    .pipe( plugins.cssmin() )
    .pipe( plugins.rename( { suffix : '.min' } ) )
    .pipe( gulp.dest( './assets/css' ) )
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

/**
 * Clean gulp cache
 */
gulp.task( 'clear', function () {
    cache.clearAll();
} );


/**
 * Clean tasks for zip
 *
 * Being a little overzealous, but we're cleaning out the build folder, codekit-cache directory and annoying DS_Store
 * files and Also clearing out unoptimized image files in zip as those will have been moved and optimized
 */

gulp.task( 'cleanup', function () {
    return del([
        config.buildDirectory + '**/*'
    ]);
} );

gulp.task( 'cleanupFinal', function () {
    return del([
        config.buildDirectory + 'build-files' + '**/*'
    ]);
} );

/**
 * Build task that moves essential theme files for production-ready sites
 *
 * buildFiles copies all the files in buildInclude to build folder - check variable values at the top
 * buildImages copies all the images from img folder in assets while ignoring images inside raw folder if any
 */

gulp.task( 'buildFiles', function () {

    return gulp.src( config.buildIncludeFiles, {read: false})
    .pipe( gulp.dest( config.buildDirectory + 'build-files/' ) )
    .pipe( plugins.notify( { message : 'Copy from buildFiles complete', onLast : true } ) );
} );


/**
 * Zipping build directory for distribution
 *
 * Taking the build folder, which has been cleaned, containing optimized files and zipping it up to send out as an
 * installable theme
 */
gulp.task( 'buildZip', function () {
    return gulp.src( config.buildIncludeFiles )
    .pipe( plugins.zip( config.name + '.zip' ) )
    .pipe( gulp.dest( config.buildDirectory ) )
} );


// ==== TASKS ==== //
/**
 * Gulp Default Task
 *
 * Compiles styles, fires-up browser sync, watches js and php files. Note browser sync task watches php files
 *
 */

    // Package Distributable Theme
gulp.task( 'build', function ( cb ) {
    runSequence(  'cleanup', 'buildFiles','buildZip', 'cleanupFinal', cb );
} );

gulp.task( 'watch', function () {
    gulp.watch( [ config.sourceDirectory + '/sass/_variables.scss', config.sourceDirectory + '/sass/theme/**/*.scss', config.sourceDirectory + '/sass/main.scss', config.sourceDirectory + '/sass/*.scss', config.sourceDirectory + '/sass/bootstrap.scss' ], [ 'sass' ] );
    gulp.watch( [ config.sourceDirectory + '/js/main.js', config.sourceDirectory + '/js/theme/*.js' ], [ 'js-main' ] );
    gulp.watch( [ config.sourceDirectory + '/js/plugins.js', config.sourceDirectory + '/js/vendor/**/*.js' ], [ 'js-plugins' ] );
    // Other watchers
} );


gulp.task( 'default', [ 'watch' ] );