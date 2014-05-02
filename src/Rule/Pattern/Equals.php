<?php
/**
* Compares a value against a list of predefined values and returns the matched value if
* a name was provided.
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
 * Compares a value against a list of predefined values and returns the matched value if
 * a name was provided.
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsRulePatternEquals implements PapayaModuleRedirectsRulePattern {

  private $_values = array();
  private $_currentValue = array();
  private $_name = '';


  /**
   * @param array|int|float|string|boolean $values
   * @param $currentValue
   * @param string $name
   */
  public function __construct($values, $currentValue, $name = '') {
    $this->_values = PapayaUtilArray::ensure($values);
    $this->_currentValue = $currentValue;
    $this->_name = (string)$name;
  }

  /**
   * @return array|bool
   */
  public function apply() {
    if (in_array($this->_currentValue, $this->_values)) {
      if (!empty($this->_name)) {
        return array($this->_name => $this->_currentValue);
      } else {
        return TRUE;
      }
    }
    return FALSE;
  }
}