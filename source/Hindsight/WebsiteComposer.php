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
                
      $markdownFileList = $website->getMarkdownList();
      $validatedMarkdownList = self::validateMarkdownFileList($markdownFileList);
      
      if($validatedMarkdownList !== false) {
        
      } else throw new Exception("No markdown files found, or Markdown files was invalid. Please give it another shot!");
    }

    /**
     * Validates an array of markdown file paths
     *
     * @param $markdownFileList
     * 
     * @return array a validated and filtered list of markdown files
     * @return false on failure
     */
    private static function validateMarkdownFileList($markdownFileList)
    {
      if ($markdownFileList !== false) {
        if ( is_array($markdownFileList) && (count($markdownFileList) >= 1) ) {
          /**
           * FILTER Markdown file list :
           * if each item is string and a useful file, its OK. 
           * otherwise NOT !
           */  
          $filtered = array_filter(
            $markdownFileList,
            function ($item) {
              if (is_string($item) && FileStorage::isUsefulFile($item)) {
                return true;
              } else return false;
            }
          );
          # return the filtered file list
          return $filtered;
        } else return false;
      } else return false;
    }
  }
  