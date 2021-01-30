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
  }
  