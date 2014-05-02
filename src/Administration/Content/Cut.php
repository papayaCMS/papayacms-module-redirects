<?php
/**
* Cut rule action
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
 * Cut rule action
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationContentCut
  extends PapayaUiControlCommand {

  /**
   * @var PapayaDatabaseInterfaceRecord
   */
  private $_record = NULL;

  /**
   * @var PapayaModuleRedirectsAdministrationClipboard
   */
  private $_clipboard = NULL;

  /**
   * Create a delete command
   *
   * @param PapayaDatabaseInterfaceRecord $record
   */
  public function __construct(PapayaDatabaseInterfaceRecord $record) {
    $this->_record = $record;
  }

  /**
   * Modifiy clipboard data and ouput a message for the user
   */
  public function appendTo(PapayaXmlElement $parent) {
    if (isset($this->_record)) {
      $this->clipboard()->redirectRule = iterator_to_array($this->_record);
      $this->papaya()->messages->display(
        PapayaMessage::SEVERITY_INFO,
        new PapayaUiStringTranslated(
          'Rule #%d "%s" is now on clipboard.',
          array(
            $this->_record['id'],
            $this->_record['title']
          )
        )
      );
    }
  }

  /**
   * Getter/Setter for the clipboard storage
   */
  public function clipboard(PapayaModuleRedirectsAdministrationClipboard $clipboard = NULL) {
    if (isset($clipboard)) {
      $this->_clipboard = $clipboard;
    } elseif (NULL === $this->_clipboard) {
      $this->_clipboard = new PapayaModuleRedirectsAdministrationClipboard();
    }
    return $this->_clipboard;
  }

}