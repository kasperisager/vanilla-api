<?php if (!defined('APPLICATION')) exit();

// Register API library with the Garden Autoloader
$Map        = Gdn_Autoloader::MAP_LIBRARY;
$Context    = Gdn_Autoloader::CONTEXT_APPLICATION;
$Path       = PATH_APPLICATIONS . DS . 'api/library';

$Options    = array();
$Options['Extension'] = 'api';

Gdn_Autoloader::RegisterMap($Map, $Context, $Path, $Options);