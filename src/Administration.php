<?php
/**
* 404 Redirects administration page
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
 * 404 Redirects administration page
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministration extends PapayaAdministrationPage {

  protected $_parameterGroup = 'redirects';

  /**
   * @return PapayaAdministrationPagePart
   */
  public function createNavigation() {
    return new PapayaModuleRedirectsAdministrationNavigation();
  }
  /**
   * @return PapayaAdministrationPagePart
   */
  public function createContent() {
    return new PapayaModuleRedirectsAdministrationContent();
  }
}