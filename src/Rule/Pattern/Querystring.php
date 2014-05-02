<?php
/**
* A rule match the http url query string.
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
* A rule match the http url query. If matched, it return TRUE or an array with found placeholders.
*
* The values of each parameter can be defined by a joker "*" or a placeholder "{name}". Unknown
* paramters are ignored.
*
* @package Papaya-Modules
* @subpackage Free-Redirects
*/
class PapayaModuleRedirectsRulePatternQuerystring extends PapayaModuleRedirectsRulePatternCallback {

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
        '_querystring' => $this->_current
      );
    } else {
      $pattern = $this->getQueryParts($this->_pattern);
      $current = $this->getQueryParts($this->_current);
      $result = array(
        '_querystring' => $this->_current
      );
      foreach ($pattern as $name => $value) {
        if (!isset($current[$name])) {
          return FALSE;
        } elseif ($value == '*') {
          continue;
        } elseif (substr($value, 0, 1) == '{' && substr($value, -1) == '}') {
          $result[substr($value, 1, -1)] = $current[$name];
        } elseif ($value != $current[$name]) {
          return FALSE;
        }
      }
      return $result;
    }
  }

  private function getQueryParts($querystring) {
    $query = new PapayaRequestParametersQuery();
    $query->setString($querystring);
    return $query->values()->getList();
  }
}