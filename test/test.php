<?php
  error_reporting(0);
  
  require __DIR__ . '/Seekr/Seekr.php';
  require __DIR__ . '/Seekr/Contradiction.php';
  require __DIR__ . '/Seekr/TestResult.php';
  require __DIR__ . '/Seekr/Timer.php';
  require __DIR__ . '/Seekr/Premise.php';
  require __DIR__ . '/Seekr/Say.php';

  require __DIR__ . '/HindsightTest.php';

  // this is how to use it.
  $test = new HindsightTest();
  $test->runTests();
  $test->seeTestResults();