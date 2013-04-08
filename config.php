<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$Iterator = Finder::create()
   ->files()
   ->name('*.php')
   ->in(__DIR__ . '/library')
;

return new Sami($Iterator, array(
   'title'                 => 'Vanilla API',
   'build_dir'             => __DIR__ . '/build',
   'cache_dir'             => __DIR__ . '/cache',
   'simulate_namespaces'   => true,
   'default_opened_level'  => 2,
));