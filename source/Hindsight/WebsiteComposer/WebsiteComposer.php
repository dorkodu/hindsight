<?php
  namespace Hindsight\WebsiteComposer;

  use Exception;
  use Parsedown;
  use Hindsight\FileStorage;
  use Hindsight\WebsiteComposer\PageSeeder;


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
        
        if ($seedData !== false && is_array($seedData)) {
          
          # keep that seeded html in memory
          $seededTemplate = PageSeeder::seed($templateString, $seedData);
          
          # for each markdown file,
          foreach ($markdownFileList as $markdownFile) {
            self::createHTMLFileFromMarkdown($seededTemplate, $markdownFile, $website->getDirectory());
          }

        } else throw new Exception("Couldn't resolve your seed data from hindsight.json.");
      }
    }

    /**
     * Creates an HTML file for each Markdown file
     *
     * @param string $markdownFile
     * 
     * @return true on success
     * @return false on failure
     */
    private static function createHTMLFileFromMarkdown(string $template, string $markdownPath, string $rootDirectory)
    {
      $markdownPageName = basename($markdownPath, ".md");
      
      $parsedMarkdown = self::parseMarkdownToHTML($markdownPath);

      if ($parsedMarkdown !== false) {

        # markdown -> parse to HTML -> embed to seeded html -> save that html file
        $contents = PageSeeder::replaceToken(PageSeeder::TOKEN_MARKDOWN, $parsedMarkdown, $rootDirectory);
        $htmlPath = $rootDirectory . "/" . $markdownPageName . ".html";        

        if (!FileStorage::isUsefulFile( $htmlPath )) {
          $result = FileStorage::createFile($htmlPath);

          if ($result !== false) {
            if (!FileStorage::putFileContents($htmlPath, $contents))
              throw new Exception("Couldn't write your contents to : '" . $markdownPageName . ".html'");
          } else throw new Exception("Couldn't create HTML file : '" . $markdownPageName. ".html'");
        } else { 
          if (!FileStorage::putFileContents($htmlPath, $contents))
            throw new Exception("Couldn't write your contents to : '" . $markdownPageName . ".html'");
        }
      } else throw new Exception("Couldn't parse your Markdown.");
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