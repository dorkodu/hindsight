<?php
  error_reporting(E_ALL);

  require "publisher/HindsightPublisher.php";

  HindsightPublisher::publish("./source", ".");
