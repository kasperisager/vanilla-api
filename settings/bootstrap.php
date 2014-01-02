<?php if (!defined('APPLICATION')) exit;

$map     = Gdn_Autoloader::MAP_LIBRARY;
$context = Gdn_Autoloader::CONTEXT_APPLICATION;
$path    = PATH_APPLICATIONS . DS . 'api/library';
$options = array('Extension' => 'api');

// Register API library with the Garden Autoloader
Gdn_Autoloader::registerMap($map, $context, $path, $options);
