<?php
/**
* Administration interface content part
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
 * Administration interface content part
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationContent
  extends PapayaAdministrationPagePart {

  /**
   * @var PapayaModuleRedirectsContentRedirect
   */
  private $_redirect;

  /**
   * Initialize navigation controller structure if needed
   *
   * @param string $name
   * @param string $default
   * @return PapayaUiControlCommand
   */
  protected function _createCommands($name = 'cmd', $default = 'edit') {
    $commands = parent::_createCommands($name, $default);
    $commands['cut'] = new PapayaModuleRedirectsAdministrationContentCut($this->redirect());
    $commands['delete'] = new PapayaModuleRedirectsAdministrationContentDelete($this->redirect());
    $commands['edit'] = new PapayaModuleRedirectsAdministrationContentChange($this->redirect());
    $commands['move'] = new PapayaModuleRedirectsAdministrationContentMove();
    $commands['try'] = new PapayaModuleRedirectsAdministrationContentTry();
    return $commands;
  }

  public function redirect(PapayaModuleRedirectsContentRedirect $redirect = NULL) {
    if (isset($redirect)) {
      $this->_redirect = $redirect;
    } elseif (NULL == $this->_redirect) {
      $this->_redirect = new PapayaModuleRedirectsContentRedirect();
      $this->_redirect->activateLazyLoad($this->parameters()->get('id', 0));
    }
    return $this->_redirect;
  }

}