const through = require('through2');
const gutil = require('gulp-util');
const spawn = require( 'child_process' ).spawn;
const PLUGIN_NAME = 'gulp-tale-jade';

function compile(file, callback, options) {

  var args = [
    __dirname + '/support/compile-jade.php',
    '--file', file.path,
  ];

  for (var key in options) {
    if (options[key]) {
      args.push( '--' + key );
    }
  }

  var command = spawn( 'php', args);
  
  var result = new Buffer('');
  
  command.stdout.on('data', function(data) {
    result = Buffer.concat([result, new Buffer( data.toString() )]);
  });
  
  command.stderr.on('data', function(data) {
    result = Buffer.concat([result, new Buffer( data.toString() )]);
  });

  command.on('close', function(code) {
    if (code > 0) {
      var err = new gutil.PluginError(PLUGIN_NAME, {
        message: result.toString()
      });
      return callback(err, file);
    }
    file.contents = result;
    file.path = file.path.replace(/\.(php\.)?(jade|jd)$/, '.php');
    return callback(null, file);
  });
}

function gulpTaleJade(options) {

  options = options || {};

  return through.obj(function(file, enc, cb) {
    
    if (file.isNull()) {
      return cb(null, file);
    }

    if (file.isBuffer()) {
      return compile(file, cb, options);
    }

    if (file.isStream()) {
      return cb(new gutil.PluginError(PLUGIN_NAME, { message: 'No support for streams' }), file);
    }

  });

}

module.exports = gulpTaleJade;
