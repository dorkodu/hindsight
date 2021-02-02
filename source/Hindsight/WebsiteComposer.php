<?php
  namespace Hindsight;

  use Exception;
  use Parsedown;
  use Hindsight\Json\JsonFile;
  use Hindsight\Settler\SettingsResolver;
  use Hindsight\PageSeeder;

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
      $markdownFileList = $website->getMarkdownList();
      $validatedMarkdownList = self::validateMarkdownFileList($markdownFileList);
      
      # if has any files ...
      if($validatedMarkdownList !== false) {
        # get html template string
        $templateString = $website->getHTMLTemplate();
        # get js data array
        $seedData = $website->getSeedData();
        
        if ($seedData !== false) {
          # keep that seeded html in memory
          $seededTemplate = PageSeeder::seed($templateString, $seedData);
          
          # for each markdown file,
          foreach ($markdownFileList as $markdownFile) {
            $parsedMarkdown = self::parseMarkdownToHTML($markdownFile);
            
            if ($parsedMarkdown !== false) {
              # markdown -> parse to HTML -> embed to seeded html -> save that html file
              $contents = PageSeeder::replaceToken(PageSeeder::TOKEN_MARKDOWN, $parsedMarkdown, $seededTemplate);
            } else throw new Exception("Couldn't parse your Markdown.");
            
          }
          
        } else throw new Exception("Couldn't resolve your seed data from hindsight.json.");
      } else throw new Exception("No Markdown files found, or file list was invalid. Please give it another shot!");
    }

    /**
     * Parses a given Markdown file contents to HTML
     *
     * @param string $markdownPath
     * 
     * @return string markdown file path
     * @return false on failure
     */
    private static function parseMarkdownToHTML(string $markdownPath)
    {
      if (FileStorage::isUsefulFile($markdownPath)) {
        $markdownContents = FileStorage::getFileContents($markdownPath);
        
        $parsedown = new Parsedown();
        $html = $parsedown->text($markdownPath);
         # prints: <p>Hello <em>Parsedown</em>!</p>
         return $html;
      } else return false;
    }

    /**
     * Validates an array of markdown file paths
     *
     * @param $markdownFileList
     * 
     * @return array a validated and filtered list of markdown files
     * @throws Exception on failure
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

        } else throw new Exception("No Markdown files found. Please, create your contents first :)");
      } else throw new Exception("Invalid Markdown file list. Please give it another shot!");
    }
  }