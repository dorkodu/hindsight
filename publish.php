<?php
  error_reporting(E_ERROR);

  require "publisher/HindsightPublisher.php";
  
  # error handler
  set_error_handler( function($e) {
    HindsightPublisher::breakRunning("ERROR", $e->__toString());
  } );

  # exception handler
  set_exception_handler( function($e) {
    HindsightPublisher::breakRunning("EXCEPTION", $e->__toString());
  } );

  # publish Hindsight
  HindsightPublisher::publish("./source", ".");