<?php
  require "TerminalUI.php";
  require "CLITinkerer.php";
  require "PharPublisher.php";

  use TerminalUI;
  use CLITinkerer;
  use PharPublisher;
  
  class HindsightPublisher
  {
    public static function publish(string $sourcePath, string $publishPath)
    {
      $simplestPhar = new PharPublisher('hindsight.phar', $sourcePath, $publishPath);
      $simplestPhar->setDefaultStub("bootstrap.php");
      $simplestPhar->publish();

      $newName = "hindsight";
      $successRename = rename("hindsight.phar", $newName);
      $successCopy = copy("hindsight", "sample/hindsight");
    }

    public static function runTests() 
    {
      $output = array();
      $result = null;
      exec("php test/test.php", $output, $result);
    }

    private static function consoleLog($text)
    {
      TerminalUI::bold("HindsightPublisher");
      CLITinkerer::write(" > ". $text);
      CLITinkerer::breakLine();
    }

    private static function breakRunning($topic, $content)
    {
      TerminalUI::bold("Hindsight > ");
      TerminalUI::bold($topic);
      CLITinkerer::write(": ".$content);
      CLITinkerer::breakLine();
      exit;
    }  
  }