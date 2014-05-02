<?php
/**
* Encapsulates a value into a pattern rule. Allow to add into the logical rules.
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
 * Encapsulates a value into a pattern rule. Allow to add into the logical rules.
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsRulePatternValue implements PapayaModuleRedirectsRulePattern {

  private $_value = FALSE;

  public function __construct($value) {
    $this->_value = $value;
  }

  /**
   * @return array|bool
   */
  public function apply() {
    return is_array($this->_value) ? $this->_value : (bool)$this->_value;
  }
}