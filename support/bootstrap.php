<?php

// Loaded by the phug CLI via --bootstrap before compiling.
// gulp-phug passes its settings through environment variables.

\Phug\Phug::setOption('debug', getenv('GULP_PHUG_DEBUG') === '1');
