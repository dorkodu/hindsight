<?php
  namespace Hindsight\Settler;

  use Hindsight\Json\JsonFile;
  use Hindsight\Json\JsonPreprocessor;

  class SettingsResolver
  {
    /**
     * Returns the data array for a given website project
     *
     * @param array $rootArray the root array of hindsight.json
     * 
     * @return array the data array for a website project
     * @return false on failure
     */
    private static function getDataArray($rootArray)
    {
      if (!empty($rootArray)) {
        return self::getArrayFromArray("data", $rootArray);
      } else return false; # stupid required array
    }

    /**
     * Returns an array element from an array
     * 
     * @return false on failure
     * @return array on success
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
      $data = self::getDataArray($jsonAssocArray);

      if ($data !== false) {
        /**
         * This is how to use it
         * $attribute = self::getArrayFromArray('attribute', $data);
         */

        return $data;
      } else return false; # at any error
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