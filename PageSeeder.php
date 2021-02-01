<?php
  namespace Hindsight;
  
  class PageSeeder
  {
    private const TOKEN_PATTERN = "/{{ ([a-zA-Z0-9$-_.]+) }}/";

    /**
     * Seeds data to a text
     *
     * @param string $template A text to seed
     * @param array $data A data array with key-value pairs
     *
     * @return string seeded HTML template
     */
    public static function seed(string $template, array $data)
    {
      $combinedTokens = self::parseTokensWithKeys($template, self::TOKEN_PATTERN);
      print_r($combinedTokens);

      $contents = self::replacePlaceholders($template, $combinedTokens, $data);

      return $contents;
    }
    
    /**
     * Parses a text and returns data for parsing
     *
     * @return array parse results
     * @return false on failure
     */
    public static function parseHTMLTemplate(string $template)
    {
      if($template !== "") {
        preg_match_all(self::TOKEN_PATTERN, $template, $results);
        return $results;
      } else return false;
    }

    /**
     * Parses tokens with keys, use this to seed template
     *
     * @param string $template
     * @param string $tokenPattern ~ RegEx pattern for tokens
     *
     * @return array
     */
    public static function parseTokensWithKeys(string $template, string $tokenPattern)
    {
      $parsed = parseHTMLTemplate($template, $tokenPattern);
      $keys = getKeysFromParseResults($parsed);
      $tokens = getTokensFromParseResults($parsed);

      $keyWithTokenList = array_combine($keys, $tokens);
      return $keyWithTokenList;
    }
  }
  