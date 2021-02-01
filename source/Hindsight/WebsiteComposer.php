<?php
  namespace Hindsight;

  use Exception;
  use Hindsight\Json\JsonFile;
  use Hindsight\Settler\SettingsResolver;

  class WebsiteComposer
  {
    /**
     * Takes a WebsiteProject object and generates a new website from it
     *
     * @param WebsiteProject $website
     * @return void
     */
    public static function compose(WebsiteProject $website)
    {
       # get all markdown files list
          # if has any files, 
          # get html template string
          # get js data array
          # generate a seeded html
          # keep that seeded html in memory
       # for each markdown file, 
          # markdown -> parse 2 HTML -> embed to seeded html -> save that html file    
    }
  }
  