<?php
/**
* Creates a rule structure for an url, depending on the pattern data.
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
 * Creates a rule structure for an url, depending on the pattern data.
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsRulePatternUrl extends PapayaModuleRedirectsRulePatternCallback {

  private $_data = array();
  private $_url = NULL;

  public function __construct(array $data, PapayaUrl $url) {
    parent::__construct(array($this, 'callbackCreateRule'));
    $this->_data = $data;
    $this->_url = $url;
  }

  public function callbackCreateRule() {
    $rules = new PapayaModuleRedirectsRulePatternAnd();
    $rules->add(
      new PapayaModuleRedirectsRulePatternScheme(
        PapayaUtilArray::get($this->_data, 'scheme', 0),
        $this->_url->getScheme()
      )
    );
    $rules->add(
      new PapayaModuleRedirectsRulePatternHost(
        PapayaUtilArray::get($this->_data, 'host', ''),
        $this->_url->getHost()
      )
    );
    $rules->add(
      new PapayaModuleRedirectsRulePatternQuerystring(
        PapayaUtilArray::get($this->_data, 'querystring', '*'),
        $this->_url->getQuery()
      )
    );
    switch (PapayaUtilArray::get($this->_data, 'mode', 'url')) {
    case 'page' :
      $rules->add(
        new PapayaModuleRedirectsRulePatternPage(
          $this->_data, $this->_url
        )
      );
      break;
    case 'url' :
    default :
      $rules->add(
        new PapayaModuleRedirectsRulePatternPath(
          PapayaUtilArray::get($this->_data, 'path', ''),
          $this->_url->getPath()
        )
      );
    }
    return $rules;
  }
}