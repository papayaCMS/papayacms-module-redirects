<?php
/**
* A rule match a integer number against a range.
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
* A rule match a integer number against a range.
*
* The pattern is a comma separated list of integers and integer ranges. An integer range is
* an minimum and a maxmimum number separated by a minus.
*
* @package Papaya-Modules
* @subpackage Free-Redirects
*/
class PapayaModuleRedirectsRulePatternRange implements PapayaModuleRedirectsRulePattern {

  /**
   * @var string
   */
  private $_pattern = '';
  /**
   * @var integer
   */
  private $_current = 0;
  /**
   * @var string
   */
  private $_name = '';

  /**
   * @param string $pattern
   * @param integer $current
   * @param string $name
   */
  public function __construct($pattern, $current, $name = '') {
    $this->_pattern = (string)$pattern;
    $this->_current = (int)$current;
    $this->_name = (string)$name;
  }

  public function apply() {
    if ($this->_pattern === '' || $this->_pattern === '*') {
      return empty($this->_name) ? TRUE : array($this->_name => $this->_current);
    } else {
      $pattern = $this->getPatternParts($this->_pattern);
      foreach ($pattern as $range) {
        if (is_array($range)) {
          if ($this->_current >= $range[0] && $this->_current <= $range[1]) {
            return empty($this->_name) ? TRUE : array($this->_name => $this->_current);
          }
        } elseif ($this->_current === $range) {
          return empty($this->_name) ? TRUE : array($this->_name => $this->_current);
        }
      }
      return FALSE;
    }
  }

  private function getPatternParts($string) {
    $found = preg_match_all(
      '((?:(?P<min>\\d+)\\-(?P<max>\\d+))|(?P<exactly>\\d+))',
      $string,
      $matches,
      PREG_SET_ORDER
    );
    $result = array();
    if ($found) {
      foreach ($matches as $match) {
        if (isset($match['exactly'])) {
          $result[] = (int)$match['exactly'];
        } else {
          $result[] = array((int)$match['min'], (int)$match['max']);
        }
      }
    }
    return $result;
  }
}