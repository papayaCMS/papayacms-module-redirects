<?php
/**
* Makes the second rule depend on the first rule, if the first rule matches, the result of
* the second rule is returned.
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
 * Makes the second rule depend on the first rule,if the first rule mathces, the result of
 * the second rule is returned.
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsRulePatternIf implements PapayaModuleRedirectsRulePattern {

  private $_condition = NULL;
  private $_rule = NULL;

  public function __construct(
    PapayaModuleRedirectsRulePattern $condition,
    PapayaModuleRedirectsRulePattern $rule
  ) {
    $this->_condition = $condition;
    $this->_rule = $rule;
  }

  /**
   * @return array|bool
   */
  public function apply() {
    $result = FALSE;
    if (FALSE !== $this->_condition->apply()) {
      return $this->_rule->apply();
    }
    return $result;
  }
}