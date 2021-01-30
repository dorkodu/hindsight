<?php
  namespace Hindsight\Settler;

  use Exception;
  use Hindsight\Settler\StateLocker;
  use Hindsight\FileStorage;
  use Hindsight\Json\JsonFile;

  class SampleProject
  {
    /**
     * Creates a sample project in given directory
     *
     * @param string $projectDirectory
     */
    public static function create(string $projectDirectory)
    {
      # first, create hindsight.json
      self::createHindsightJson($projectDirectory);

      # then create folders
      self::createFolder($projectDirectory, "pages");
      self::createFolder($projectDirectory, "composed");
      
      # create page.html template
      self::createPageHtml($projectDirectory);

      # create index.md template
      self::createIndexMd($projectDirectory);
      
      # create README.txt
      self::createReadmeTxt($projectDirectory);
    }
    
    
    /**
     * Creates a folder inside the project directory
     */
    private static function createHindsightJson(string $projectDirectory)
    {
      $HindsightJsonPath = $projectDirectory."/hindsight.json";
      
      if (FileStorage::createFile($HindsightJsonPath)) {

        $HindsightJson = new JsonFile($HindsightJsonPath);  
        $HindsightJsonTemplate = self::generateHindsightJsonTemplate();
        
        $HindsightJson->write($HindsightJsonTemplate, true);
  
      } else throw new Exception("Couldn't create hindsight.json file.");
    }
      
    /**
     * Creates page.html
     * 
     * @param string $projectDirectory
     * @return void
     */
    private static function createPageHtml(string $projectDirectory)
    {
      $pageHtmlPath = $projectDirectory . "/page.html";

      # create page.html
      if (FileStorage::createFile($pageHtmlPath)) {
        # write into page.html
        $pageHtmlContents = self::generateHTMLTemplate();
        if (!FileStorage::putFileContents($pageHtmlPath, $pageHtmlContents))
          throw new Exception("Couldn't write to 'page.html'.");
      } else throw new Exception("Couldn't create 'page.html'."); 
    }

    /**
     * Creates index.md
     * 
     * @param string $projectDirectory
     * @return void
     */
    private static function createIndexMd(string $projectDirectory)
    {
      $MdPath = $projectDirectory . "/composed/index.md";

      # create index.md
      if (FileStorage::createFile($MdPath)) {
        # write into page.html
        $MdContents = self::generateMarkdownTemplate();
        if (!FileStorage::putFileContents($MdPath, $MdContents))
          throw new Exception("Couldn't write to '~/composed/index.md'.");
      } else throw new Exception("Couldn't create '~/composed/index.md'."); 
    }

    /**
     * Creates README.txt
     *
     * @param string $projectDirectory
     * @return void
     */
    private static function createReadmeTxt(string $projectDirectory)
    {
      $readmePath = $projectDirectory."/README.txt";

      if (FileStorage::createFile($readmePath)) {
        # create README.txt
        $readmeContents = self::generateReadmeContent();
        if (!FileStorage::putFileContents($readmePath, $readmeContents))
          throw new Exception("Couldn't write to README.txt ~ But this isn't critical, ignore it.");
      } else throw new Exception("Couldn't create README.txt ~ But this isn't critical, ignore it."); 
    }

    /**
     * Creates a folder inside the root project directory
     *
     * @param string $projectDirectory
     * @param string $folderName just the name of the folder
     * @return void
     */
    private static function createFolder(string $projectDirectory, string $folderName)
    {
      # attempt to create a folder. if fails throw the exception!
      if (!FileStorage::createDirectory($projectDirectory."/".$folderName)) {
        throw new Exception("Couldn't create '".$folderName."' folder.");
      }
    }


    /**
     * Generates an empty, template string for hindsight.json
     * 
     * @return array the template string content of a hindsight.json file
     */
    private static function generateHindsightJsonTemplate()
    {
      return array(
        "data" => array(
          "title" => "This is a sample page title!",
          "header" => "This is a header.",
          "footer" => "<div class='footer'>This is the footer. You can write HTML for placeholders too! <br>It only needs to be a string.<div>"
        )
      );
    }
 

    /**
     * Generates a sample HTML template file content
     *
     * @return void
     */
    private static function generateHTMLTemplate()
    {
      return '
      <!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>{{ title }}</title>
        </head>
        <body>
          <h1>{{ header }}</h1>
          {{ $contents }}
          {{ footer }}
        </body>
      </html>';
    }
  }
  