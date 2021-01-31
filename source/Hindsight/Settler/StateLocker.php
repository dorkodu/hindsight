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
    * Returns the path of Hindsight.lock file in given directory
    *
    * @param $directoryPath
    * @return string path of hindsight.lock file
    * @return false on failure
    */
    private static function getLockFilePath(string $directory)
    {
      if (FileStorage::isUsefulDirectory($directory)) {
        $lockFilePath = $directory . "/" . self::LOCKFILE;
        if (FileStorage::isUsefulFile($lockFilePath)) {
          return $lockFilePath;
        } else return FileStorage::createFile($lockFilePath) 
                      ? $lockFilePath 
                      : false;
      } else return false;
    }
     

   /**
    * Generates a hash from given string. For now it uses Whirlpool hashing algorithm
    *
    * @param string $contents
    * @return string hashed content
    * @return false on failure
    */
    public static function generateLockHash(string $contents)
    {
      return Dorcrypt::whirlpool($contents);
    }
    
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
  