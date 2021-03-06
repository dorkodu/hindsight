<?php

namespace Dorkodu\Hindsight\Settler;

use Dorkodu\Hindsight\FileStorage;
use Dorkodu\Hindsight\Json\JsonFile;
use Dorkodu\Hindsight\Json\JsonPreprocessor;
use Dorkodu\Utils\Dorcrypt;

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
      } else {
        return FileStorage::createFile($lockFilePath) ? $lockFilePath : false;
      }
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
  private static function pushLockState($hash, $lockFilePath)
  {
    return FileStorage::putFileContents($lockFilePath, $hash);
  }

  /**
   * Tells whether the current state is locked to a known state
   * 
   * @param string $directory
   * @param string $contents
   * @return boolean
   */
  public static function isStateLocked(string $directory, string $contents)
  {
    $lockFilePath = self::getLockFilePath($directory);
    if ($lockFilePath !== false) {

      $persistedStateHash = self::pullLockState($lockFilePath);
      $currentStateHash = self::generateLockHash($contents);

      if (Dorcrypt::compareHash($persistedStateHash, $currentStateHash)) {
        return true;
      } else return false;
    } else return false;
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

        $currentStateHash = self::generateLockHash($contents);
        return self::pushLockState($currentStateHash, $lockFilePath);
      } else return false;
    }
  }
}
