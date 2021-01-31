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

   
  }
  