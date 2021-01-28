<?php
  error_reporting(E_ALL);

  require "publisher/HindsightPublisher.php";
  
  set_error_handler(function($e) {
    HindsightPublisher::breakRunning("ERROR", $e->__toString());
  });

  set_exception_handler(function($e) {
    HindsightPublisher::breakRunning("EXCEPTION", $e->__toString());
  });



  HindsightPublisher::publish("./source", ".");
