<?php
  use Seekr\Seekr;
  use Seekr\Say;

  /**
   * A simple Test class with a few tests
   */
  class HindsightTest extends Seekr
  {
    public function setUp() 
    {
      chdir("./sample");
    }

    public function testGreet()
    {
      exec("echo 'Hello'");
    }

    public function testAbout()
    {
      
    }

    public function testHelp()
    {
      
    }

    public function testInit()
    {
      
    }

    public function testStatus()
    {
      
    }

    public function testCompose()
    {
      
    }
  }