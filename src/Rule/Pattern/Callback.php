<?php
/**
* A rule encapsulating a callback function to create it. If the result is an array or a boolean
* it is used directly. If it is another rule, that rule ist executed.
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
* A rule encapsulating a callback function to create it. If the result is an array or a boolean
* it is used directly. If it is another rule, that rule ist executed.
*
* @package Papaya-Modules
* @subpackage Free-Redirects
*/
class PapayaModuleRedirectsRulePatternCallback implements PapayaModuleRedirectsRulePattern {

  private $_callback = NULL;

  /**
   * @param callable $callback
   */
  public function __construct($callback) {
    PapayaUtilConstraints::assertCallable($callback);
    $this->_callback = $callback;
  }

  /**
   * Use the callback. If the result is another rule, apply it. If it is an array return the array.
   * If it is neither, case the result to boolean.
   *
   * @return array|bool
   */
  public function apply() {
    $result = call_user_func($this->_callback);
    if ($result instanceof PapayaModuleRedirectsRulePattern) {
      $result = $result->apply();
    }
    if (!(is_array($result))) {
      return (bool)$result;
    }
    return $result;
  }
}