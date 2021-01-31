<?php
  namespace Hindsight;

  use Hindsight\Json\JsonFile;

  class WebsiteProject
  {
    protected string $directory;
    protected JsonFile $hindsightJson;
    protected $markdownList;

    /**
     * Class constructor.
     */
    public function __construct(string $directory)
    {
      $this->directory = realpath($directory);
    }

    /**
     * Checks if the directory is already processed by Hindsight
     *
     * @return boolean
     */
    private function isInitted()
    {
      return ( 
        FileStorage::isUsefulFile($this->directory."/hindsight.json")
        && FileStorage::isUsefulFile($this->directory."/hindsight.lock")
      );
    }

    /**
     * Checks if the directory is already processed by Hindsight
     *
     * @return boolean
     */
    private function isProject()
    {
      return (
        $this->isInitted() # is initted a project
        && FileStorage::isUsefulDirectory($this->directory."/pages/") # does have "pages" folder
      );
    }

    /**
     * Checks if the directory is already processed by Hindsight
     *
     * @return boolean
     */
    private function isCompleteProject()
    {
      return (
        $this->isProject() # is initted a project
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
     * @param string $directory
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

    
  }
  