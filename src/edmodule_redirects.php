<?php
/**
* 404 Redirect Administration
*
* @copyright 213 by papaya Software GmbH - All rights reserved.
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
* 404 Redirect Administration
*
* @package Papaya-Modules
* @subpackage Free-Redirects
*/
class edmodule_redirects extends base_module {

  const PERMISSION_MANAGE = 1;

  /**
  * Permissions
  * @var array $permissions
  */
  public $permissions = array(
    self::PERMISSION_MANAGE => 'Manage',
  );

  private $_administration = NULL;

  public $layout = NULL;

  /**
  * Execute module
  *
  * @access public
  */
  public function execModule() {
    if ($this->hasPerm(1, TRUE)) {
      $this->administration()->execute();
    }
  }

  public function administration(PapayaModuleRedirectsAdministration $administration = NULL) {
    if (isset($administration)) {
      $this->_administration = $administration;
    } elseif (NULL === $this->_administration) {
      $this->_administration = new PapayaModuleRedirectsAdministration($this->layout);
      $this->_administration->papaya($this->papaya());
    }
    return $this->_administration;
  }
}