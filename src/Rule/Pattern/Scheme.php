<?php
/**
* A rule match the http url scheme.
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
* A rule match the http url scheme. If matched, it return an array with a _scheme key.
*
* @package Papaya-Modules
* @subpackage Free-Redirects
*/
class PapayaModuleRedirectsRulePatternScheme extends PapayaModuleRedirectsRulePatternCallback {

  /**
   * @var string
   */
  private $_mode = '';
  /**
   * @var string
   */
  private $_current = '';

  /**
   * @param string $mode
   * @param string $current
   */
  public function __construct($mode, $current) {
    parent::__construct(array($this, 'callbackCreateRule'));
    $this->_mode = $mode;
    $this->_current = $current;
  }

  public function callbackCreateRule() {
    switch ($this->_mode) {
    case PapayaUtilServerProtocol::HTTP :
      return new PapayaModuleRedirectsRulePatternEquals(
        array('http'), $this->_current, '_scheme'
      );
    case PapayaUtilServerProtocol::HTTPS :
      return new PapayaModuleRedirectsRulePatternEquals(
        array('https'), $this->_current, '_scheme'
      );
    default :
      return new PapayaModuleRedirectsRulePatternEquals(
        array('http', 'https'), $this->_current, '_scheme'
      );
    }
  }
}