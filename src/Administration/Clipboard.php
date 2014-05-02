<?php
/**
* Administration clipboard, stores an element that is currently marked as "cutted"
*
* @copyright 2014 by papaya Software GmbH - All rights reserved.
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
 * Administration clipboard, stores an element that is currently marked as "cutted"
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 *
 * @property array $redirectRule
 */
class PapayaModuleRedirectsAdministrationClipboard
  extends PapayaSessionShare {

  protected $_definitions = array(
    'redirect_rule' => TRUE
  );
}