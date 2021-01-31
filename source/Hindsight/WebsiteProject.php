<?php
  namespace Hindsight;

  use Hindsight\Json\JsonFile;

  class WebsiteProject
  {
    private string $directory;
    private JsonFile $hindsightJson;
    private $markdownList; # type can vary - false|array|null - and this is useful for us

    /**
     * Class constructor.
     */
    public function __construct(string $directory)
    {
      $this->directory = realpath($directory);
    }

    public function getDirectory()
    {
      return $this->directory;
    }

    /**
     * Just a wrapper for checking project folder usefulness
     *
     * @return boolean
     */
    public function inUsefulDirectory()
    {
      return FileStorage::isUsefulDirectory($this->directory);
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
        && FileStorage::isUsefulDirectory($this->directory."/pages/") # does have "pages" folder
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
        && FileStorage::isUsefulDirectory($this->directory."/composed/") # does have "composed" folder?
      );
    }

    /**
     * Returns the 'hindsight.json' JsonFile for the project directory
     *
     * @return JsonFile $hindsightJson object
     * @return false on failure
     */
    private function getHindsightJson()
    {
      if (FileStorage::isUsefulDirectory($this->projectDirectory)) {
        # Folder is useful. Hindsight is running.
        if ($this->isInitted()) {
          $HindsightJson = new JsonFile($this->projectDirectory."/hindsight.json");
          # return HindsightJson if useful
          if ($HindsightJson->isUseful()) {
            return $HindsightJson;
          } else return false;
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
        
        $list = glob( $this->directory . "pages/*.md" );
        $markdownFileList = array();
        
        foreach ($list as $name) {
          if (FileStorage::isUsefulFile($name)) {
            array_push($markdownFileList, $name);
          }
        }
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
      $jsonContents = $this->hindsightJson->read();
      $markdownList = $this->getMarkdownFileList();
      
      $state['hindsightJson'] = is_bool($jsonContents) ? "" : $jsonContents;
      $state['markdownList'] = $this->getMarkdownFileList();
      return serialize($state);
    }
  }
  