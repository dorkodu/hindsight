<?php
  namespace Hindsight\Settler;

use Exception;
use Hindsight\Settler\StateLocker;
  use Hindsight\Settler\SettingsResolver;
  use Hindsight\FileStorage;
  use Hindsight\Utils\CLITinkerer;
  use Hindsight\Utils\TerminalUI;
  use Hindsight\Json\JsonFile;
  use Hindsight\Json\JsonPreprocessor;

  class SampleProject
  {
    public static function create(string $projectDirectory)
    {
      # hindsight.json production
      $HindsightJsonPath = $projectDirectory."/hindsight.json";

      if (FileStorage::createFile($HindsightJsonPath)) {
        $HindsightJson = new JsonFile($HindsightJsonPath);

        $HindsightJsonTemplate = self::generateHindsightJsonTemplate();
        $HindsightJson->write($HindsightJsonTemplate, true);

        if (FileStorage::createDirectory($projectDirectory."/pages")) {
          if(FileStorage::createDirectory($projectDirectory."/composed")) {
            if (FileStorage::createDirectory($projectDirectory."/assets")) {
              
            } else throw new Exception("Couldn't create 'assets' folder.");
          } else throw new Exception("Couldn't create 'composed' folder.");
        } else throw new Exception("Couldn't create 'pages' folder.");
      } else throw new Exception("Couldn't create hindsight.json file.");
    }

    /**
     * Generates an empty, template string for hindsight.json
     * 
     * @return array the template string content of a hindsight.json file
     **/
    private static function generateHindsightJsonTemplate()
    {
      return array("placeholders" => array(), "assets" => array());
    }
  }
  