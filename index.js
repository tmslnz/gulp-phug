const through = require('through2');
const spawn = require( 'child_process' ).spawn;
const PluginError = require('plugin-error');
const path = require('path');

const PLUGIN_NAME = 'gulp-phug';

const compile = (file, callback, options) => {
  let args = [
    path.join(__dirname, 'support', 'vendor', 'phug', 'phug', 'phug'),
    'compileFile', file.path,
  ];
  let command = spawn( 'php', args);
  let stdout = [];
  let stderr = [];

  command.stdout.on('data', function(data) {
    stdout.push(data);
  });

  command.stderr.on('data', function(data) {
    stderr.push(data);
  });

  command.on('error', function(error) {
    let err = new PluginError(PLUGIN_NAME, `Failed to spawn PHP: ${error.message}`);
    return callback(err, file);
  });

  command.on('close', function(code) {
    if (code > 0) {
      let errorMessage = Buffer.concat(stderr).toString() || Buffer.concat(stdout).toString();
      let err = new PluginError(PLUGIN_NAME, errorMessage);
      return callback(err, file);
    }
    file.contents = Buffer.concat(stdout);
    file.path = file.path.replace(/\.(jade|jd|pug)$/, '.php');
    return callback(null, file);
  });
}

const gulpPhug = (options = {}) => {
  return through.obj(function(file, enc, cb) {
    if (file.isNull()) return cb(null, file);
    if (file.isStream()) return cb(new PluginError(PLUGIN_NAME, 'Streaming not supported'));

    // Check if file has a valid extension
    if (!/\.(jade|jd|pug)$/.test(file.path)) {
      return cb(null, file);
    }

    if (!file.contents.length) {
      file.path = file.path.replace(/\.(jade|jd|pug)$/, '.php');
      return cb(null, file);
    }
    if (file.isBuffer()) return compile(file, cb, options);
  });
}

gulpPhug.logError = function logError(error) {
  const message = new PluginError(PLUGIN_NAME, error.messageFormatted).toString();
  process.stderr.write(`${message}\n`);
  this.emit('end');
};

module.exports = gulpPhug;
