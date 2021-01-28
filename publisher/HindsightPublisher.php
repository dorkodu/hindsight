<?php
  require "TerminalUI.php";
  require "CLITinkerer.php";
  require "PharPackager.php";
  require "Timer.php";

  class HindsightPublisher
  {
    public const PKG_NAME = "hindsight";
    
    public static function publish(string $sourceFolder, string $publishFolder)
    {
      self::consoleLog("This will build, run tests and publish Hindsight project.");
      self::removePreviousPackageIfExists($publishFolder);

      $hindsightPhar = new PharPackager('hindsight.phar', $sourceFolder, $publishFolder);
      $hindsightPhar->setDefaultStub("bootstrap.php");
      $hindsightPhar->publish();

      $successPublished = is_file("hindsight.phar");
      $successRename = rename("hindsight.phar", self::PKG_NAME);
      $successCopy = copy("hindsight", "sample/hindsight");
      
      if ($successCopy && $successPublished && $successRename) {
        self::consoleLog("Successfully published the Hindsight.");

        $resultOutput = self::runTests();

        self::consoleLog("Test Results :");
        printf("%s\n", $resultOutput);
    
        self::consoleLog("All tests have been run.");
        self::consoleLog("DONE.");
      } else throw new Exception("An unknown problem occured on publishing Hindsight.");
    }

    public static function packageAlreadyExists($directory)
    {
      return is_file(realpath($directory)."hindsight");
    }

    public static function removePreviousPackageIfExists($directory)
    {
      if(self::packageAlreadyExists($directory)) {
        return unlink(realpath($directory)."/hindsight");
      }
    }

    /**
     * Runs all Hindsight tests and returns the output
     *
     * @return string
     */
    public static function runTests() 
    {
      $output = array();
      $result = null;
      exec("php test/test.php", $output, $result);

      return implode("\n", $output);
    }

    public static function consoleLog($text)
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