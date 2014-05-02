<?php
/**
* Try to match an test url against the rules.
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
 * Try to match an test url against the rules.
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationContentTry
  extends PapayaUiControlCommandDialog {

  /**
   * @var PapayaModuleRedirectsContentRedirects
   */
  private $_redirects;

  public function createDialog() {
    $dialog = parent::createDialog();
    $dialog->parameterGroup($this->parameterGroup());
    $dialog->caption = new PapayaUiStringTranslated('Try An Url');
    $dialog->hiddenFields()->merge(
      array(
        'cmd' => 'try'
      )
    );

    $dialog->fields[] = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Url'),
      'url',
      0,
      '',
      new PapayaFilterUrl()
    );
    $dialog->fields[] = new PapayaUiDialogFieldSelectRadio(
      new PapayaUiStringTranslated('Apply disabled'),
      'use_disabled',
      new PapayaUiStringTranslatedList(
        array(
          1 => 'Yes',
          0 => 'No'
        )
      )
    );
    $dialog->buttons[] = new PapayaUiDialogButtonSubmit(
      new PapayaUiStringTranslated('Apply')
    );
    $this->callbacks()->onExecuteSuccessful = array($this, 'callbackApplyRules');
    return $dialog;
  }

  public function callbackApplyRules($context, $dialog, PapayaXmlElement $parent) {
    $url = new PapayaUrl($this->dialog()->data()->get('url', ''));
    foreach ($this->redirects() as $redirect) {
      $rules = new PapayaModuleRedirectsRulePatternUrl(
        PapayaUtilArray::ensure($redirect['pattern']), $url
      );
      if (FALSE !== ($data = $rules->apply())) {
        $listview = new PapayaUiListview();
        $listview->papaya($this->papaya());
        $listview->parameterGroup($this->parameterGroup());
        $listview->caption = new PapayaUiStringTranslated('Match');
        $listview->items[] = $item = new PapayaUiListviewItem(
          $redirect['enabled'] ? 'items-link' : 'status-link-locked',
          $redirect['title'],
          array(
            'cmd' => 'edit',
            'id' => $redirect['id']
          )
        );
        $item->emphased = TRUE;
        $item->columnSpan = 2;
        $listview->items[] = $item = new PapayaUiListviewItem(
          '', new PapayaUiStringTranslated('Origin')
        );
        $item->subitems[] = new PapayaUiListviewSubitemText((string)$url);
        $listview->items[] = $item = new PapayaUiListviewItem(
          '', new PapayaUiStringTranslated('Target')
        );
        $item->subitems[] = new PapayaUiListviewSubitemText(
          $this->getTargetReference(
            PapayaUtilArray::ensure($redirect['target']),
            $data
          )->get()
        );
        $listview->items[] = $item = new PapayaUiListviewItem(
          '', new PapayaUiStringTranslated('Status')
        );
        $item->subitems[] = new PapayaUiListviewSubitemText(
          $redirect['status']
        );
        $listview->items[] = $item = new PapayaUiListviewItem(
          '', new PapayaUiStringTranslated('Placeholders')
        );
        $item->columnSpan = 2;
        ksort($data);
        foreach ($data as $name => $value) {
          $listview->items[] = $item = new PapayaUiListviewItem('', $name);
          $item->indentation = 1;
          $item->subitems[] = new PapayaUiListviewSubitemText($value);
        }
        $parent->append($listview);
        return;
      }
    }
  }

  public function getTargetReference($configuration, $current) {
    $target = new PapayaModuleRedirectsRuleMatch($configuration, $current);
    $target->papaya($this->papaya());
    return $target->getReference();
  }

  public function redirects(PapayaModuleRedirectsContentRedirects $redirects = NULL) {
    if (isset($redirects)) {
      $this->_redirects = $redirects;
    } elseif (NULL == $this->_redirects) {
      $this->_redirects = new PapayaModuleRedirectsContentRedirects();
      $this->_redirects->papaya($this->papaya());
      $this->_redirects->activateLazyLoad(
        $this->dialog()->data()->get('use_disabled', FALSE)
          ? NULL : array('enabled' => TRUE)
      );
    }
    return $this->_redirects;
  }
}