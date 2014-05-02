<?php
/**
* Combines two or more rules into a list. Rules in the list is executed and the returned array
* are merged until a first rule returns FALSE.
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
 * Combines two or more rules into a list. Rules in the list is executed and the returned array
 * are merged until a first rule returns FALSE.
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsRulePatternAnd implements PapayaModuleRedirectsRulePattern {

  private $_rules = array();

  public function __construct() {
    foreach (func_get_args() as $rule) {
      $this->add($rule);
    }
  }

  /**
   * @param PapayaModuleRedirectsRulePattern $rule
   */
  public function add(PapayaModuleRedirectsRulePattern $rule) {
    $this->_rules[] = $rule;
  }

  /**
   * @return array|bool
   */
  public function apply() {
    $result = FALSE;
    /** @var PapayaModuleRedirectsRulePattern $rule */
    foreach ($this->_rules as $rule) {
      if ($data = $rule->apply()) {
        if (is_array($result) && is_array($data)) {
          $result = PapayaUtilArray::merge($result, $data);
        } elseif (is_array($result)) {
          continue;
        } else {
          $result = $data;
        }
      } else {
        return FALSE;
      }
    }
    return $result;
  }
}