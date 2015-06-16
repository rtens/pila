<?php

use org\rtens\isolation\Runner;

require_once __DIR__ . '/vendor/autoload.php';

var_dump((new Runner(__DIR__ . '/src/libraries', \org\rtens\isolation\Library::class))->run());