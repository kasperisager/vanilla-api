<?php if (!defined('APPLICATION')) exit();

// Bootstrap Swagger PHP
require PATH_APPLICATIONS . '/api/vendor/Swagger/Swagger.php';

// Bootstrap Doctrine Common
use Doctrine\Common\ClassLoader;
require PATH_APPLICATIONS . '/api/vendor/Doctrine/Common/ClassLoader.php';
$commonLoader = new ClassLoader('Doctrine\Common', PATH_APPLICATIONS . '/api/vendor');
$commonLoader->register();