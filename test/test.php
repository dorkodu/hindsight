<?php
  error_reporting(E_ALL);

  require __DIR__ . '/Seekr/seekr_autoloader.php';
  require __DIR__ . '/HindsightTest.php';

  // this is how to use it.
  $test = new HindsightTest();
  $test->runTests();
  $test->seeTestResults();