<?php
  require_once "Hindsight/Utils/Psr4Autoloader.php";
  require_once "Hindsight/Utils/CLITinkerer.php";
  
  use \Hindsight\Utils\Psr4Autoloader;
  use \Hindsight\Utils\CLITinkerer;
  use \Hindsight\Hindsight;

  $psr4Autoloader = new Psr4Autoloader();
  $psr4Autoloader->usePharMethod("hindsight.phar");
  $psr4Autoloader->register();
  
  # registering all namespaces used in Hindsight
  $psr4Autoloader->addNamespace("Hindsight\\", "Hindsight/");

  # a global problem handler for the CLI app
  $problemHandler = function($exception) {
    Hindsight::problem($exception->getMessage());
  };

  # set problem handlers by default
  set_error_handler($problemHandler);
  set_exception_handler($problemHandler);

  $command = CLITinkerer::getArgument(1);

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