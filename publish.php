<?php
  error_reporting(E_ALL);
  require "PharPublisher.php";

  $greet = function () {
    echo "> Dorkodu Phar Publisher";
    echo PHP_EOL."> This code will build and publish Hindsight.";
  };

  $simplestPhar = new PharPublisher('hindsight.phar', './source', '.');
  $simplestPhar->setBeforeEffect($greet);
  $simplestPhar->setDefaultStub("bootstrap.php");
  $simplestPhar->publish();

  $newName = "hindsight";
  rename("hindsight.phar", $newName);
  echo "> Newest Hindsight is :: $newName".PHP_EOL.PHP_EOL;
