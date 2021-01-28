<?php
  namespace Hindsight\Settler;

  use Hindsight\FileStorage;
  use Hindsight\Json\JsonFile;
  use Hindsight\Json\JsonPreprocessor;
  use Hindsight\Utils\Dorcrypt;

  class StateLocker
  {
    /**
     * Tells whether the current state is locked to a known state
     * 
     * @param JsonFile $jsonFile the Hindsight.json file to check for
     * @return bool true on success, false on failure
     **/
    public static function isCurrentStateLocked(JsonFile $jsonFile)
    {
      $jsonContent = $jsonFile->read();
      if ($jsonContent !== false) {
        $directoryPath = FileStorage::getDirectoryPath($jsonFile->getPath());
        $lockFilePath = self::getLockFilePath($directoryPath);
        if ($lockFilePath !== false) {
          $persistedState = self::pullLockState($lockFilePath);
          $currentState = self::generateLockHash($jsonContent);
          if (Dorcrypt::compareHash($currentState, $persistedState)) {
            return true;
          } else return false;
        } else return false;
      } else return false;
    }

    /**
     * Returns the path of Hindsight.lock file in given directory
     *
     * @param $directoryPath
     * @return string path of Hindsight.lock file
     * @return false on failure
     */
    private static function getLockFilePath($directoryPath)
    {
      if (FileStorage::isUsefulDirectory($directoryPath)) {
        $lockFilePath = $directoryPath.'/hindsight.lock';
        if (FileStorage::isUsefulFile($lockFilePath)) {
         return $lockFilePath;
        } else return FileStorage::createFile($lockFilePath) 
                      ? $lockFilePath 
                      : false;
      } else return false;
    }

    /**
     * Generates a hash from given content. For now it uses Whirlpool hashing algorithm
     *
     * @param string $content
     * @return string hashed content
     * @return false on failure
     */
    private static function generateLockHash($content)
    {
      return Dorcrypt::whirlpool($content);
    }

    /**
     * Gets the lock hash from given file path
     * 
     * @param string $filePath the Hindsight.lock file path
     * @return false when the content is empty or file is useless
     * @return string the content of Hindsight.lock file
     **/
    private static function pullLockState($filePath)
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
     **/
    private static function pushLockState($hash, $filePath)
    {
      return FileStorage::putFileContents($filePath, $hash);
    }

    /**
     * Locks the dependency to the current state
     * @param JsonFile $jsonFile the hindsight.json of the project
     * @return false on failure
     * @return true on success
     */
    public static function lock(string $contents, string $directory)
    {
      $lockFilePath = self::getLockFilePath($directory);
      if ($lockFilePath !== false) {
        $currentState = self::generateLockHash($jsonContent);
        return self::pushLockState($currentState, $lockFilePath);
      } else return false;
    }
  }
  