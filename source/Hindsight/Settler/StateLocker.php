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
    * Gets the lock hash from given file path
    * 
    * @param string $filePath the Hindsight.lock file path
    * @return false when the content is empty or file is useless
    * @return string the content of Hindsight.lock file
    */
    private static function pullLockState(string $filePath)
    {
      $hashContent = FileStorage::getFileContents($filePath);
      if (is_string($hashContent) && !empty($hashContent)) {
        return $hashContent;
      } else return false;
    }

   /**
    * Puts the lock hash to given file path
    * 
    * @param string $hash content that to put in Hindsight.lock
    * @param string $filePath the Hindsight.lock file path
    * 
    * @return false on failure
    * @return true on success
    */
    private static function pushLockState($hash, $directoryPath)
    {
      return FileStorage::putFileContents($directoryPath . "/" . self::LOCKFILE, $hash);
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
  