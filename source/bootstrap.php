<?php

require_once "Dorkodu/Utils/Psr4Autoloader.php";
require_once "Dorkodu/Utils/Console.php";

use Dorkodu\Hindsight\Hindsight;

use Dorkodu\Utils\Console;
use Dorkodu\Utils\Psr4Autoloader;

$psr4Autoloader = new Psr4Autoloader();
$psr4Autoloader->usePharMethod("hindsight.phar");
$psr4Autoloader->register();

# registering all namespaces used in Hindsight
$psr4Autoloader->addNamespace("Dorkodu", "Dorkodu");

# a global problem handler for the CLI app
$problemHandler = function ($exception) {
  Hindsight::problem($exception->getMessage());
};

# set problem handlers by default
set_error_handler($problemHandler);
set_exception_handler($problemHandler);

$command = Console::getArgument(1);

# start the app :)
$hindsight = new Hindsight(".");

/**
 * Hindsight's simple router :D
 */
switch ($command) {
  case 'about':
    $hindsight->about();
    break;
  case 'help':
    $hindsight->help();
    break;
  case 'init':
    $hindsight->init();
    break;
  case 'compose':
    $hindsight->compose();
    break;
  case 'status':
    $hindsight->status();
    break;
  default:
    $hindsight->greet();
    break;
}
