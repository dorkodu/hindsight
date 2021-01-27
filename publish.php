<?php
  error_reporting(E_ALL);

  require "HindsightPublisher.php";

  HindsightPublisher::publish("./source", ".");
