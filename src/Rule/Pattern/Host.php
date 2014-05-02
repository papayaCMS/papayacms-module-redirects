<?php
/**
* A rule match the http url hostname.
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
* A rule match the http url hostname. If matched, it return an array with a _host key.
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
class PapayaModuleRedirectsRulePatternHost extends PapayaModuleRedirectsRulePatternCallback {

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
        '_host' => $this->_current
      );
    } else {
      $patternParts = array_reverse(explode('.', $this->_pattern));
      if (FALSE === strpos($this->_current, '.')) {
        $currentParts = array($this->_current);
      } else {
        $currentParts = array_reverse(explode('.', $this->_current));
      }
      $result = array();
      $allowSubdomains = FALSE;
      $length = count($patternParts);
      for ($i = 0; $i < $length; ++$i) {
        if (isset($currentParts[$i])) {
          $part = $patternParts[$i];
          if ($part === '*') {
            $allowSubdomains = TRUE;
          } elseif (substr($part, 0, 1) == '{' && substr($part, -1) == '}') {
            $allowSubdomains = FALSE;
            $result[substr($part, 1, -1)] = $currentParts[$i];
          } elseif ($part !== $currentParts[$i]) {
            return FALSE;
          }
        } elseif (!$allowSubdomains) {
          return FALSE;
        } else {
          break;
        }
      }
      $result['_host'] = $this->_current;
      return $result;
    }
  }
}