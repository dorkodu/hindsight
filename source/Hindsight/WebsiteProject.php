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
  }
  