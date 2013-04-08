<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
   ->files()
   ->name('*.php')
   ->exclude('vendors')
   ->exclude('components')
   ->exclude('build')
   ->exclude('cache')
   ->in(__DIR__)
;

return new Sami($iterator, array(
   'title'                 => 'Vanilla API',
   'build_dir'             => __DIR__ . '/build',
   'cache_dir'             => __DIR__ . '/cache',
   'default_opened_level'  => 2,
));