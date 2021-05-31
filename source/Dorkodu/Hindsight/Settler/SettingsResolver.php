<?php

namespace Dorkodu\Hindsight\Settler;

use Dorkodu\Hindsight\Json\JsonFile;
use Dorkodu\Hindsight\Json\JsonPreprocessor;

class SettingsResolver
{
  /**
   * Returns an array element from an array
   *
   * @param int|string $needle
   * @param array $haystack
   *
   * @return array on success
   * @return false on failure
   */
  private static function getArrayFromArray($needle, array $haystack)
  {
    if (array_key_exists($needle, $haystack)) {
      if (is_array($haystack[$needle])) {
        return $haystack[$needle]; # returns if a desired array
      } else {
        return array($haystack[$needle]); # puts in an array, if not an array lol :P 
      }
    } else return false; # not a desired array
  }

  /**
   * Parses the 'data' attribute of the root array of hindsight.json and 
   * returns a meaningful data array
   * 
   * @param array $jsonAssocArray
   * @return void
   */
  private static function resolveData(array $jsonAssocArray)
  {
    $data = self::getArrayFromArray("data", $jsonAssocArray);
    # return if successfully get "data" sub-array from the root jsonAssocArray
    return ($data !== false) ? $data : false;
  }

  /**
   * Resolves settings for a website project
   * 
   * @param JsonFile jsonFile object for hindsight.json file
   * 
   * @return array root array.
   * @return false on failure
   */
  public static function resolve(JsonFile $jsonFile)
  {
    if ($jsonFile->isUseful()) {
      $jsonContent = $jsonFile->read();

      if ($jsonContent !== false) {

        $jsonArray = JsonPreprocessor::parseJson($jsonContent);
        $rootArray = array();

        $dataArray = self::resolveData($jsonArray);

        if ($dataArray !== false) {
          $rootArray["data"] = $dataArray;
        }

        return $rootArray;
      } else return false;
    } else return false;
  }
}
