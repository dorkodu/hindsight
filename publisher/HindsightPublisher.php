<?php
  require "TerminalUI.php";
  require "CLITinkerer.php";
  require "PharPublisher.php";

  class HindsightPublisher
  {
    public static function publish(string $sourcePath, string $publishPath)
    {
      self::consoleLog("This will build, run tests and publish Hindsight project.");

      $hindsightPhar = new PharPublisher('hindsight.phar', $sourcePath, $publishPath);
      $hindsightPhar->setDefaultStub("bootstrap.php");
      $hindsightPhar->setAfterEffect(function() {
        self::consoleLog("Build and published Hindsight successfully.");
      });
      $hindsightPhar->publish();

      $name = "hindsight";
      $successRename = rename("hindsight.phar", $name);
      $successCopy = copy("hindsight", "sample/hindsight");

      $resultArray = self::runTests();
      $resultOutput = implode("\n", $resultArray);

      self::consoleLog("Output :");
      printf("%s\n", $resultOutput);
    }

    public static function runTests() 
    {
      $output = array();
      $result = null;
      exec("php test/test.php", $output, $result);

      return $output;
    }

    private static function consoleLog($text)
    {
      TerminalUI::bold("Hindsight Publisher");
      CLITinkerer::write(" > ". $text);
      CLITinkerer::breakLine();
    }

    private static function breakRunning($topic, $content)
    {
      TerminalUI::bold("Hindsight Publisher > ");
      TerminalUI::bold($topic);
      CLITinkerer::write(": ".$content);
      CLITinkerer::breakLine();
      exit;
    }
  }