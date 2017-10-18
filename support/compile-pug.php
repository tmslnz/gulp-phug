<?php

use Tale\Pug;

include 'vendor/autoload.php';

// Option parsing
//
$longopts = array(
    'file:',
    'ttl:',
    'paths:',
    'cachepath:',
    'pretty',
    'standalone',
);

$opts = getopt('', $longopts);

$file = null;
if ( isset($opts['file']) ) $file = $opts['file'];

$renderOptions = [];
if ( isset($opts['paths']) ) $renderOptions['paths'] = preg_split("/[\s,]+/", $opts['paths']);
if ( isset($opts['pretty']) ) $renderOptions['pretty'] = true;
if ( isset($opts['standalone']) ) $renderOptions['stand_alone'] = true;
if ( isset($opts['cachepath']) ) $renderOptions['cache_path'] = $opts['cachepath'];
if ( isset($opts['ttl']) ) $renderOptions['ttl'] = $opts['ttl'];

// Send compiled output to STDOUT
//
function compile ($file, array $options = null ) {
    $compiler = new Pug\Compiler($options);
    return $compiler->compileFile($file);
}

try {
    fwrite(STDOUT, compile($file, $renderOptions));
    exit(0);
} catch (Exception $e) {
    fwrite(STDERR, $e->getMessage());
    exit(1);
}
