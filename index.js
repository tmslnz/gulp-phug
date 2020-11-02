const through = require('through2');
const spawn = require( 'child_process' ).spawn;
const PluginError = require('plugin-error');

const PLUGIN_NAME = 'gulp-phug';

const compile = (file, callback) => {
  let args = [
    __dirname + '/support/vendor/phug/phug/phug',
    'compileFile', file.path,
  ];
  let command = spawn( 'php', args);
  let result = Buffer.from('');

  command.stdout.on('data', function(data) {
    result = Buffer.concat([result, Buffer.from( data.toString() )]);
  });

  command.stderr.on('data', function(data) {
    result = Buffer.concat([result, Buffer.from( data.toString() )]);
  });

  command.on('close', function(code) {
    if (code > 0) {
      let err = new PluginError(PLUGIN_NAME, result.toString());
      return callback(err, file);
    }
    file.contents = result;
    file.path = file.path.replace(/\.(jade|jd|pug)$/, '.php');
    return callback(null, file);
  });
}

const gulpPhug = (options = {}) => {
  return through.obj(function(file, enc, cb) {
    if (file.isNull()) return cb(null, file);
    if (file.isStream()) return cb(new PluginError(PLUGIN_NAME, 'Streaming not supported'));
    if (!file.contents.length) {
      file.path = file.path.replace(/\.(jade|jd|pug)$/, '.php');
      return cb(null, file);
    }
    if (file.isBuffer()) return compile(file, cb, options);
  });
}

gulpPhug.logError = function logError(error) {
  const message = new PluginError('phug', error.messageFormatted).toString();
  process.stderr.write(`${message}\n`);
  this.emit('end');
};

module.exports = gulpPhug;
