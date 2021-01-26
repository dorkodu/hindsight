<?php
  require_once "Hindsight/Utils/Psr4Autoloader.php";
  
  use \Hindsight\Utils\Psr4Autoloader;
  use \Hindsight\Hindsight;

  error_reporting(0);


  $psr4Autoloader = new Psr4Autoloader();
  $psr4Autoloader->usePharMethod("Hindsight.phar");
  $psr4Autoloader->register();
  
  # registering all namespaces used in Hindsight
  $psr4Autoloader->addNamespace("Hindsight\\", "Hindsight/");
  $psr4Autoloader->addNamespace("Hindsight\\Dependency\\", "Hindsight/Dependency/");
  $psr4Autoloader->addNamespace("Hindsight\\Json\\", "Hindsight/Json/");
  $psr4Autoloader->addNamespace("Hindsight\\Utils\\", "Hindsight/Utils/");
  $psr4Autoloader->addNamespace("Hindsight\\Weaver\\", "Hindsight/Weaver/");


  # application logic :D
  $Hindsight = new Hindsight(realpath("."));
  $Hindsight->run();