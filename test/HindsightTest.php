<?php

use Dorkodu\Seekr\Test\TestCase;

class HindsightTest extends TestCase
{

  public function setUp()
  {
    if (!(chdir(__DIR__) && chdir("../sample"))) {
      throw new Exception("Hindsight cannot change directory");
    }
  }

  /**
   * Executes a Hindsight command and outputs it
   *
   * @param $command
   * @return string returns the output on success
   * @return false on failure
   */
  private static function executeHindsightCommand(string $command)
  {
    if (!is_string($command))
      $command = "";

    $output = array();
    $directive = sprintf("php hindsight %s", $command);
    exec($directive, $output);

    $outputString = implode("\n", $output);
    printf($outputString);
  }

  public function testGreet()
  {
    self::executeHindsightCommand("");
  }

  public function testAbout()
  {
    self::executeHindsightCommand("about");
  }

  public function testHelp()
  {
    self::executeHindsightCommand("help");
  }

  public function testInit()
  {
    self::executeHindsightCommand("init");
  }

  public function testStatus()
  {
    self::executeHindsightCommand("status");
  }

  public function testCompose()
  {
    self::executeHindsightCommand("compose");
  }
}
