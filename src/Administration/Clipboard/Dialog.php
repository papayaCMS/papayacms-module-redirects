<?php
/**
* Display the current redirect rule clipboard status, and allow to clear the clipboard
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
 * Display the current redirect rule clipboard status, and allow to clear the clipboard
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationClipboardDialog
  extends PapayaUiControlCommandDialog {

  /**
   * @var PapayaModuleRedirectsAdministrationClipboard
   */
  private $_clipboard = NULL;

  public function createDialog() {
    if (is_array($this->clipboard()->redirectRule)) {
      $dialog = parent::createDialog();
      $dialog->caption = new PapayaUiStringTranslated('Clipboard');
      $dialog->parameterGroup($this->parameterGroup());

      $listview = new PapayaUiListview();
      $listview->items[] = new PapayaUiListviewItem(
        'items-link',
        $this->clipboard()->redirectRule['title']
      );
      $dialog->fields[] = $field = new PapayaUiDialogFieldListview($listview);
      $dialog->buttons[] = new PapayaUiDialogButtonSubmit(new PapayaUiStringTranslated('Clear'));
      $this->callbacks()->onExecuteSuccessful = array($this, 'callbackExecuteSuccessful');
      return $dialog;
    }
    return FALSE;
  }

  public function callbackExecuteSuccessful() {
    $this->clipboard()->redirectRule = NULL;
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
