<?php
  /**
   * A simple Test class with a few tests
   */
  class HindsightTest
  {
    private $outputs = [];

    public function setUp() 
    {
      if(!(chdir(__DIR__) && chdir("../sample"))) {
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

    public function runTests()
    {
      $this->setUp();
      
      # create a reflection class
      $reflectionClass = new \ReflectionClass( $this );
      $this->testClassName = $reflectionClass->getName();
      $methodsList = $reflectionClass->getMethods();

      # run every test
      foreach($methodsList as $method)
      {
        $methodname = $method->getName();
        if ( strlen( $methodname ) > 4 && substr( $methodname, 0, 4 ) == 'test' ) {
          # condition above means this is a test method, if so mounts it !
          $commandName = strtolower(substr($methodname, 4));

          ob_start();
          try {
            $this->$methodname(); # run test method
          } catch( \Exception $ex ) {
            self::consoleLog($ex->__toString());
          }

          # get output
          $output = ob_get_clean();
          $this->outputs[$commandName] = $output;
        }
      }
    }

    public function seeTestResults()
    {
      foreach ($this->outputs as $command => $output) {
        self::consoleLog($command . " : \n" . $output . "\n");
      }
    }
    
    /**
     * Prints a message.
     *
     * @param string $contents
     * @return void
     */
    public static function consoleLog($contents = "") 
    {
      printf("\n\033[1mHindsight Test >\033[0m %s", $contents);
    }
  }