<?php

require __DIR__ . '/HindsightTest.php';

error_reporting(E_ERROR);

// this is how to use it.
$test = new HindsightTest();
$test->runTests();
$test->seeTestResults();
