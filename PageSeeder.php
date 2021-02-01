<?php
  namespace Hindsight;
  
  class PageSeeder
  {
    /**
     * Seeds data to a text
     *
     * @param string $template
     * @param array $data
     *
     * @return string seeded HTML template
     */
    public static function seed(string $template, array $data)
    {
      $pattern = "/{{ ([a-zA-Z0-9$-_.]+) }}/";

      $combinedTokens = self::parseTokensWithKeys($template, $pattern);
      print_r($combinedTokens);

      $contents = self::replacePlaceholders($template, $combinedTokens, $data);

      return $contents;
    }
  }
  