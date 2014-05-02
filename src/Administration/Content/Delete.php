<?php
/**
* Delete rule action
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
 * Delete rule action
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationContentDelete
  extends PapayaUiControlCommandDialogDatabaseRecord {

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
    parent::__construct($record, self::ACTION_DELETE);
  }

  protected function createDialog() {
    $dialog = parent::createDialog();
    $dialog->caption = new PapayaUiStringTranslated('Delete Rule');
    /** @var PapayaModuleRedirectsContentRedirect $record */
    $record = $this->record();
    if ($record->isLoaded()) {
      $dialog->parameterGroup($this->owner()->parameterGroup());
      $dialog->hiddenFields()->merge(
        array(
          'cmd' => 'delete',
          'id' => $record->id
        )
      );
      $dialog->fields[] = new PapayaUiDialogFieldInformation(
        new PapayaUiStringTranslated(
          'Delete rule "%s" #%d?',
          array(
            $record->title,
            $record->id
          )
        ),
        'places-trash'
      );
      $dialog->buttons[] = new PapayaUiDialogButtonSubmit(
        new PapayaUiStringTranslated('Delete')
      );
      $this->callbacks()->onExecuteSuccessful = array($this, 'callbackExecuteSuccessful');
    } else {
      $dialog->fields[] = new PapayaUiDialogFieldMessage(
        PapayaMessage::SEVERITY_WARNING,
        'Can not find redirect rule.'
      );
    }
    return $dialog;
  }

  public function callbackExecuteSuccessful() {
    $this->papaya()->messages->display(
      PapayaMessage::SEVERITY_INFO, new PapayaUiStringTranslated('Redirect rule deleted.')
    );
    /** @var PapayaModuleRedirectsContentRedirect $record */
    $record = $this->record();
    if (is_array($this->clipboard()->redirectRule) &&
        $this->clipboard()->redirectRule['id'] == $record->id) {
      $this->clipboard()->redirectRule = NULL;
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