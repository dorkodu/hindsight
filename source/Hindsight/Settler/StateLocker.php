<?php
  namespace Hindsight\Settler;

  use Hindsight\FileStorage;
  use Hindsight\Json\JsonFile;
  use Hindsight\Json\JsonPreprocessor;
  use Hindsight\Utils\Dorcrypt;

  class StateLocker
  {
    private const LOCKFILE = "hindsight.lock";
    
   
   /**
    * Locks the dependency to the current state
    *
    * @param string $contents
    * @param string $directory
    * @return boolean isSucceed ?
    */
    public static function lock(string $contents, string $directory)
    {
      if (self::isStateLocked($directory, $contents)) {
        return true;
      } else {
        $lockFilePath = self::getLockFilePath($directory);
        if ($lockFilePath !== false) {
          $currentState = self::generateLockHash($contents);
          return self::pushLockState($currentState, $lockFilePath);
       } else return false;
      }
    }
  }
  