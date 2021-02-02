<?php
  namespace Hindsight;

  use Hindsight\Json\JsonFile;
  use Hindsight\Settler\SettingsResolver;

  class WebsiteProject
  {
    private string $directory;
    private $hindsightJson;
    private $markdownList; # type can vary - false|array|null - and this is useful for us
    private $htmlTemplate;
    private $seedData;

    private const TEMPLATE_FILE = "page.html";

    /**
     * Class constructor.
     */
    public function __construct(string $directory)
    {
      $this->directory = realpath($directory);

      # get everything from the directory
      $this->importDataFromDirectory();
    }

    public function getDirectory()
    {
      return $this->directory;
    }

    public function getMarkdownList()
    {
      return $this->markdownList;
    }

    public function getHTMLTemplate()
    {
      return $this->htmlTemplate;
    }

    public function getSeedData() {
      return $this->seedData;
    }

    /**
     * Checks if the directory is already processed by Hindsight
     *
     * @return boolean
     */
    public function isInitted()
    {
      return (
           FileStorage::isUsefulDirectory($this->directory)
        && FileStorage::isUsefulFile($this->directory."/hindsight.json")
        && FileStorage::isUsefulFile($this->directory."/hindsight.lock")
      );
    }

    /**
     * Checks if the directory is already processed by Hindsight
     *
     * @return boolean
     */
    public function isProject()
    {
      return (
        $this->isInitted()
        && FileStorage::isUsefulDirectory( $this->directory . "/pages/" ) # does have "pages" folder
      );
    }

    /**
     * Checks if the directory is already processed by Hindsight
     *
     * @return boolean
     */
    public function isCompleteProject()
    {
      return (
        $this->isProject()
        && FileStorage::isUsefulDirectory( $this->directory . "/composed/" ) # does have "composed" folder?
      );
    }

    /**
     * Imports website project data from the directory, into this object
     *
     * @return void
     */
    private function importDataFromDirectory()
    {
      # get JSON
      $this->hindsightJson = $this->getHindsightJson();
      # get HTML
      $this->htmlTemplate = $this->getHTMLTemplateContents();
      # get Markdown File List
      $this->markdownList = $this->getMarkdownFileList();
      # parse data from JSON
      $this->seedData = $this->resolveSeedData();
    }

    /**
     * Resolve seed data
     *
     * @return array the "data" field of root JSON
     * @return false on failure
     */
    private function resolveSeedData()
    {
      if ($this->hindsightJson !== false) {
        $rootArray = SettingsResolver::resolve($this->hindsightJson);
        return $dataArray = (isset($rootArray['data']) && $rootArray !== false) ? $rootArray['data'] : false;
      } else return false;
    }

    /**
     * Returns the 'hindsight.json' JsonFile for the project directory
     *
     * @return JsonFile $hindsightJson object
     * @return false on failure
     */
    private function getHindsightJson()
    {
      if (FileStorage::isUsefulDirectory($this->directory)) {
        # Folder is useful. Hindsight is running.
        if ($this->isInitted()) {
          $HindsightJson = new JsonFile($this->directory."/hindsight.json");
          # return HindsightJson if useful
          if ($HindsightJson->isUseful()) {
            return $HindsightJson;
          } else return false;
        } else return false;
      } else return false;
    }

    /**
     * Gets HTML template contents
     *
     * @return string HTML template contents
     * @return false on failure
     */
    private function getHTMLTemplateContents()
    {
      if ($this->isInitted()) {
        if (FileStorage::isUsefulFile($this->directory . "/" . self::TEMPLATE_FILE)) {
          $contents = file_get_contents($this->directory . "/" . self::TEMPLATE_FILE);
          return $contents;
        } else return false;        
      } else return false;
    }

    /**
     * Get Markdown file list in a project directory
     *
     * @return array list of markdown files in a directory
     * @return false on failure
     */
    private function getMarkdownFileList()
    {
      if (FileStorage::isUsefulDirectory($this->directory)) {
        
        $list = glob( $this->directory . "/pages/*.md" );
        $markdownFileList = array();
        
        foreach ($list as $name) {
          if (FileStorage::isUsefulFile($name)) {
            array_push($markdownFileList, $name);
          }
        }
        # return the list
        return $markdownFileList;
      
      } else return false;
    }
    
    /**
     * Generates a state string by serializing the project properties
     *
     * @return string
     */
    public function getState()
    {
      $state = array();
      
      # RESOLVE hindsight.json 
      if ($this->hindsightJson !== false) {

        $jsonContents = $this->hindsightJson->read();
        $state['hindsightJson'] = is_bool($jsonContents) ? "" : $jsonContents;
      
      } else {
        $state['hindsightJson'] = "";
      }

      # RESOLVE markdown list
      if ($this->markdownList !== false) {
        $state['markdownList'] = is_bool($this->markdownList) ? array() : $this->markdownList;
      } else {
        $state['markdownList'] = array();
      }

      # serialize what you got, return it
      return serialize($state);
    }
  }
  