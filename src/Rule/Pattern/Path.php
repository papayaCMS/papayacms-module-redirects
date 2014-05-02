<?php
/**
* A rule match the http url path.
*
* @copyright 2013 by papaya Software GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Modules
* @subpackage Free-Redirects
*/

/**
* A rule match the http url path. If matched, it return an array with a _path key.
*
* Each part of the url can be a joker "*" or a named placeholder "{name}". The values of named
* placeholder are added to the result array. If the first element is an joker, subdomains are
* matched, too.
*
* Placeholders and jokers can only replace a full part of a domain. The parts are separated
* by dots.
*
* @package Papaya-Modules
* @subpackage Free-Redirects
*/
class PapayaModuleRedirectsRulePatternPath extends PapayaModuleRedirectsRulePatternCallback {

  /**
   * @var string
   */
  private $_pattern = '';
  /**
   * @var string
   */
  private $_current = '';

  /**
   * @param string $pattern
   * @param string $current
   */
  public function __construct($pattern, $current) {
    parent::__construct(array($this, 'callbackCreateRule'));
    $this->_pattern = $pattern;
    $this->_current = $current;
  }

  public function callbackCreateRule() {
    if ($this->_pattern === '' || $this->_pattern == '*') {
      return array(
        '_path' => $this->_current
      );
    } else {
      $patternParts = $this->getPathParts($this->_pattern);
      $currentParts = $this->getPathParts($this->_current);
      $allowStartsWith = TRUE;
      $result = array();
      foreach ($patternParts as $index => $part) {
        if (isset($currentParts[$index])) {
          if ($part == '*') {
            $allowStartsWith = TRUE;
            continue;
          } elseif (substr($part, 0, 1) == '{' && substr($part, -1) == '}') {
            $allowStartsWith = FALSE;
            $result[substr($part, 1, -1)] = $currentParts[$index];
          } elseif ($part != $currentParts[$index]) {
            return FALSE;
          } else {
            $allowStartsWith = FALSE;
          }
        } elseif (!$allowStartsWith) {
          return FALSE;
        } else {
          break;
        }
      }
      $result['_path'] = $this->_current;
      return $result;
    }
  }

  private function getPathParts($path) {
    if (preg_match_all('(/(?P<parts>[^/]*))u', $path, $match)) {
      return $match['parts'];
    }
    return array();
  }
}