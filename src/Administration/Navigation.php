<?php
/**
* Administration interface navigation part
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
 * Administration interface navigation part
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationNavigation
  extends PapayaAdministrationPagePart {

  /**
   * Initialize navigation controller structure if needed
   *
   * @param string $name
   * @param string $default
   * @return PapayaUiControlCommandController
   */
  protected function _createCommands($name = 'cmd', $default = 'edit') {
    $commands = parent::_createCommands($name, $default);
    $commands['edit'] = new PapayaUiControlCommandList(
      new PapayaModuleRedirectsAdministrationClipboardDialog(),
      new PapayaModuleRedirectsAdministrationNavigationListview(),
      new PapayaModuleRedirectsAdministrationNavigationToolbar($this->toolbar()->elements)
    );
    return $commands;
  }

}