<?php if (!defined("APPLICATION")) exit;

$path = paths(PATH_APPLICATIONS, "api/library");

// Register API library with the Garden Autoloader
Gdn_Autoloader::registerMap(
  Gdn_Autoloader::MAP_LIBRARY
, Gdn_Autoloader::CONTEXT_APPLICATION
, $path
, ["Extension" => "api"]
);

// Include Composer autoloader
require_once paths($path, "vendors/autoload.php");
