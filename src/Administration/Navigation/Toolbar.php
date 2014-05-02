<?php
/**
* Navigation toolbar for administration interface
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
 *  Navigation listview for administration interface
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationNavigationToolbar
  extends PapayaUiControlCommandToolbar {

  /**
   * @var PapayaModuleRedirectsAdministrationClipboard
   */
  private $_clipboard = NULL;

  public function appendToolbarElements() {
    $elements = $this->elements();
    $elements[] = $button = new PapayaUiToolbarButton();
    $button->caption = new PapayaUiStringTranslated('Add rule');
    $button->image = 'actions-generic-add';
    $button->reference()->setParameters(
      array(
        'cmd' => 'edit',
        'id' => 0
      ),
      $this->parameterGroup()
    );
    if ($this->parameters()->get('id', 0) > 0) {
      $elements[] = $button = new PapayaUiToolbarButton();
      $button->caption = new PapayaUiStringTranslated('Delete rule');
      $button->image = 'actions-generic-delete';
      $button->reference()->setParameters(
        array(
          'cmd' => 'delete',
          'id' => $this->parameters()->get('id', 0)
        ),
        $this->parameterGroup()
      );
      if (!is_array($this->clipboard()->redirectRule) ||
          $this->clipboard()->redirectRule['id'] != $this->parameters()->get('id', 0)) {
        $elements[] = $button = new PapayaUiToolbarButton();
        $button->caption = new PapayaUiStringTranslated('Cut');
        $button->image = 'actions-edit-cut';
        $button->reference()->setParameters(
          array(
            'cmd' => 'cut',
            'id' => $this->parameters()->get('id', 0)
          ),
          $this->parameterGroup()
        );
      }
    }
    $elements[] = new PapayaUiToolbarSeparator();
    if ($this->parameters()->get('id', 0) > 0) {
      if (is_array($this->clipboard()->redirectRule) &&
          $this->clipboard()->redirectRule['id'] != $this->parameters()->get('id', 0)) {
        $elements[] = $button = new PapayaUiToolbarButton();
        $button->caption = new PapayaUiStringTranslated('Insert before');
        $button->image = 'actions-edit-paste';
        $button->reference()->setParameters(
          array(
            'cmd' => 'move',
            'order' => 'before',
            'id' => $this->parameters()->get('id', 0),
            'source_id' => $this->clipboard()->redirectRule['id']
          ),
          $this->parameterGroup()
        );
        $elements[] = $button = new PapayaUiToolbarButton();
        $button->caption = new PapayaUiStringTranslated('Insert after');
        $button->image = 'actions-edit-paste';
        $button->reference()->setParameters(
          array(
            'cmd' => 'move',
            'order' => 'after',
            'id' => $this->parameters()->get('id', 0),
            'source_id' => $this->clipboard()->redirectRule['id']
          ),
          $this->parameterGroup()
        );
      }
    }
    $elements[] = new PapayaUiToolbarSeparator();
    $elements[] = $button = new PapayaUiToolbarButton();
    $button->caption = new PapayaUiStringTranslated('Try Url');
    $button->image = 'actions-execute';
    $button->reference()->setParameters(
      array(
        'cmd' => 'try'
      ),
      $this->parameterGroup()
    );
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