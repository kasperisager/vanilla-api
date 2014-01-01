<?php if (!defined('APPLICATION')) exit;

$Map     = Gdn_Autoloader::MAP_LIBRARY;
$Context = Gdn_Autoloader::CONTEXT_APPLICATION;
$Path    = PATH_APPLICATIONS . DS . 'api/library';
$Options = array('Extension' => 'api');

// Register API library with the Garden Autoloader
Gdn_Autoloader::RegisterMap($Map, $Context, $Path, $Options);
