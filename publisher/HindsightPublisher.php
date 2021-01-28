<?php
  require "TerminalUI.php";
  require "CLITinkerer.php";
  require "PharPackager.php";
  require "Timer.php";
  require "PerformanceProfiler.php";

  class HindsightPublisher
  {
    public static function publish(string $sourcePath, string $publishPath)
    {
      $profiler = new PerformanceProfiler(6, 2);
      $profiler->start();

      self::consoleLog("This will build, run tests and publish Hindsight project.");

      $hindsightPhar = new PharPackager('hindsight.phar', $sourcePath, $publishPath);
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

      $profiler->stop();

      echo "\n";
      self::consoleLog( 
        sprintf("It took %.3f seconds ~ %s to publish Hindsight."
        ,$profiler->passedTime() 
        ,$profiler->memoryPeakUsage()
        )
      );
    }

    public static function runTests() 
    {
      $output = array();
      $result = null;
      exec("php test/test.php", $output, $result);

      return $output;
    }

    public  static function consoleLog($text)
    {
      TerminalUI::bold("Hindsight Publisher");
      CLITinkerer::write(" > ". $text);
      CLITinkerer::breakLine();
    }

    public static function breakRunning($topic, $content)
    {
      TerminalUI::bold("Hindsight Publisher > ");
      TerminalUI::bold($topic);
      CLITinkerer::write(": ".$content);
      CLITinkerer::breakLine();
      exit;
    }
  }