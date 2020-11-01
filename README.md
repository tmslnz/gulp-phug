[![npm](https://img.shields.io/npm/v/gulp-tale-jade.svg?maxAge=2592000?style=flat-square)](https://www.npmjs.com/package/gulp-tale-jade)

# Gulp Tale Jade

Gulp plugin for [Tale Jade](http://jade.talesoft.codes).  
_Eventually to use [Phug](https://github.com/phug-php)._

# Usage

Example `gulpfile.js`:

```js
const plumber = require( 'gulp-plumber' );
const browsersync = require( 'browser-sync' ).create();
const jade = require( 'gulp-phug' );

gulp.task( 'default', [ 'templates', /* … */ ] );

gulp.task( 'watch', ['browsersync'], function () {
    gulp.watch( 'templates/**/*.jade', [ 'templates' ]).on('change', browsersync.reload);
});

gulp.task( 'templates', function () {
    return gulp.src( [ 'templates/*.jade' ] )
        .pipe( plumber() )
        .pipe( jade() )
        .pipe( gulp.dest( /* DESTINATION */ ) );
});
```

## Development Setup

Uses `composer` which can be installed via Homebrew with `brew install composer`
