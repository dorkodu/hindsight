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
  }
  