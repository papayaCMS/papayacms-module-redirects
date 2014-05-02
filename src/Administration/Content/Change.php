<?php
/**
* Change rule action, add/edit redirect rule
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
 * Change rule action, add/edit redirect rule
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationContentChange
  extends PapayaUiControlCommandDialogDatabaseRecord {

  /**
   * @var PapayaModuleRedirectsAdministrationClipboard
   */
  private $_clipboard = NULL;

  /**
   * @var Iterator
   */
  private $_extensions = NULL;

  protected function createDialog() {
    $dialog = parent::createDialog();

    /** @var PapayaModuleRedirectsContentRedirect $record */
    $record = $this->record();
    $patternMode = PapayaUtilArray::get(
      PapayaUtilArray::ensure($record->pattern),
      'mode',
      PapayaModuleRedirectsContentRedirect::MODE_URL
    );
    $targetMode = PapayaUtilArray::get(
      PapayaUtilArray::ensure($record->target),
      'mode',
      PapayaModuleRedirectsContentRedirect::MODE_URL
    );
    $dialog->parameterGroup($this->owner()->parameterGroup());
    $dialog->options()->topButtons = TRUE;
    $dialog->options()->bottomButtons = TRUE;
    $dialog->caption = new PapayaUiStringTranslated('Redirect Rule Pattern');

    $dialog->hiddenFields()->merge(
      array(
        'cmd' => 'edit',
        'id' => $record->id
      )
    );

    $dialog->fields[] = $field = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Title'),
      'title',
      255,
      '',
      new PapayaFilterLogicalAnd(
        new PapayaFilterNotEmpty(),
        new PapayaFilterNoLinebreak()
      )
    );
    $field->setMandatory(TRUE);
    $dialog->fields[] = $field = new PapayaUiDialogFieldSelectRadio(
      new PapayaUiStringTranslated('Label'),
      'label',
      array(
        PapayaModuleRedirectsContentRedirect::LABEL_NONE =>
          new PapayaUiStringTranslated('none'),
        PapayaModuleRedirectsContentRedirect::LABEL_IMPORTANT =>
          new PapayaUiStringTranslated('important')
      )
    );
    $dialog->fields[] = new PapayaUiDialogFieldSelectRadio(
      new PapayaUiStringTranslated('Enabled'),
      'enabled',
      new PapayaUiStringTranslatedList(
        array(
          1 => 'Yes',
          0 => 'No'
        )
      )
    );
    $dialog->fields[] = $field = new PapayaUiDialogFieldSelectRadio(
      new PapayaUiStringTranslated('Status'),
      'status',
      array(
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other'
      )
    );
    $field->setDefaultValue(301);
    $dialog->fields[] = $field = new PapayaUiDialogFieldSelectRadio(
      new PapayaUiStringTranslated('Pattern Mode'),
      'pattern/mode',
      new PapayaUiStringTranslatedList(
        array(
          PapayaModuleRedirectsContentRedirect::MODE_URL => 'Url',
          PapayaModuleRedirectsContentRedirect::MODE_PAGE => 'papaya Page'
        )
      )
    );
    $field->setDefaultValue(PapayaModuleRedirectsContentRedirect::MODE_URL);
    $dialog->fields[] = $field = new PapayaUiDialogFieldSelectRadio(
      new PapayaUiStringTranslated('Target Mode'),
      'target/mode',
      new PapayaUiStringTranslatedList(
        array(
          PapayaModuleRedirectsContentRedirect::MODE_URL => 'Url',
          PapayaModuleRedirectsContentRedirect::MODE_PAGE => 'papaya Page'
        )
      )
    );
    $field->setDefaultValue(PapayaModuleRedirectsContentRedirect::MODE_URL);
    $dialog->fields[] = $group = new PapayaUiDialogFieldGroup(
      new PapayaUiStringTranslated('Pattern')
    );
    $group->fields[] = new PapayaUiDialogFieldSelect(
      new PapayaUiStringTranslated('Scheme'),
      'pattern/scheme',
      array(
        PapayaUtilServerProtocol::BOTH => new PapayaUiStringTranslated('Any'),
        PapayaUtilServerProtocol::HTTP => 'http',
        PapayaUtilServerProtocol::HTTPS => 'https'
      )
    );
    $group->fields[] = $field = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Host'),
      'pattern/host',
      255,
      '',
      new PapayaModuleRedirectsFilterHost()
    );
    $field->setHint(
      new PapayaUiStringTranslated('Use * or {placeholder} for dynamic parts: *.domain.{tld}')
    );
    if ($patternMode == PapayaModuleRedirectsContentRedirect::MODE_URL) {
      $group->fields[] = $field = new PapayaUiDialogFieldInput(
        new PapayaUiStringTranslated('Path'),
        'pattern/path',
        255,
        '',
        new PapayaModuleRedirectsFilterPath()
      );
      $field->setHint(
        new PapayaUiStringTranslated('Use * or {placeholder} for dynamic parts: /path/{subpath}/*')
      );
    } else {
      $group->fields[] = $field = new PapayaUiDialogFieldInput(
        new PapayaUiStringTranslated('Page Id'),
        'pattern/page_id',
        255,
        '',
        new PapayaModuleRedirectsFilterRange()
      );
      $field->setHint(
        new PapayaUiStringTranslated('A comma separated list of page ids and ranges: 1,2,3,10-20')
      );
      $group->fields[] = $field = new PapayaUiDialogFieldInput(
        new PapayaUiStringTranslated('Category Id'),
        'pattern/category_id',
        255,
        '',
        new PapayaModuleRedirectsFilterRange()
      );
      $field->setHint(
        new PapayaUiStringTranslated('A comma separated list of page ids and ranges: 1,2,3,10-20')
      );
      $group->fields[] = $field = new PapayaUiDialogFieldSelectLanguage(
        new PapayaUiStringTranslated('Language'),
        'pattern/language',
        NULL,
        PapayaUiDialogFieldSelectLanguage::OPTION_ALLOW_ANY |
        PapayaUiDialogFieldSelectLanguage::OPTION_USE_IDENTIFIER
      );
      $group->fields[] = $field = new PapayaUiDialogFieldSelect(
        new PapayaUiStringTranslated('Extension'),
        'pattern/extension',
        $this->extensions()
      );
      $field->callbacks()->getOptionCaption = array($this, 'callbackGetExtensionCaption');
    }
    $group->fields[] = $field = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Parameters'),
      'pattern/querystring',
      255,
      '',
      new PapayaModuleRedirectsFilterQuerystring()
    );
    $field->setHint(
      new PapayaUiStringTranslated(
        'Use * or {placeholder} for dynamic parameter values: foo={value}&bar=*'
      )
    );
    $dialog->fields[] = $group = new PapayaUiDialogFieldGroup(
      new PapayaUiStringTranslated('Target')
    );
    $group->fields[] = new PapayaUiDialogFieldSelect(
      new PapayaUiStringTranslated('Scheme'),
      'target/scheme',
      array(
        PapayaUtilServerProtocol::BOTH => new PapayaUiStringTranslated('Any'),
        PapayaUtilServerProtocol::HTTP => 'http',
        PapayaUtilServerProtocol::HTTPS => 'https'
      )
    );
    $group->fields[] = $field = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Host'),
      'target/host',
      255,
      '*',
      new PapayaModuleRedirectsFilterHost()
    );
    $field->setHint(
      new PapayaUiStringTranslated(
        'Use captured placeholders for dynamic hosts: {placeholder}.domain.tld'
      )
    );
    if ($targetMode == PapayaModuleRedirectsContentRedirect::MODE_URL) {
      $group->fields[] = $field = new PapayaUiDialogFieldInput(
        new PapayaUiStringTranslated('Path'),
        'target/path',
        255,
        '*',
        new PapayaFilterLogicalOr(
          new PapayaFilterEquals('*'),
          new PapayaModuleRedirectsFilterPath()
        )
      );
      $field->setHint(
        new PapayaUiStringTranslated(
          'Use captured placeholders for dynamic parts: /foo/{placeholder}/bar'
        )
      );
    } else {
      $group->fields[] = $field = new PapayaUiDialogFieldInput(
        new PapayaUiStringTranslated('Page Id'),
        'target/page_id',
        255,
        '*',
        new PapayaFilterLogicalOr(
          new PapayaFilterEquals('*'),
          new PapayaFilterInteger()
        )
      );
      $group->fields[] = $field = new PapayaUiDialogFieldInput(
        new PapayaUiStringTranslated('Category Id'),
        'target/category_id',
        255,
        '*',
        new PapayaFilterLogicalOr(
          new PapayaFilterEquals('*'),
          new PapayaFilterInteger()
        )
      );
      $group->fields[] = $field = new PapayaUiDialogFieldSelectLanguage(
        new PapayaUiStringTranslated('Language'),
        'target/language',
        NULL,
        TRUE
      );
      $group->fields[] = $field = new PapayaUiDialogFieldSelect(
        new PapayaUiStringTranslated('Extension'),
        'target/extension',
        $this->extensions()
      );
      $field->callbacks()->getOptionCaption = array($this, 'callbackGetExtensionCaption');
    }
    $group->fields[] = $field = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Parameters'),
      'target/querystring',
      255,
      '*',
      new PapayaModuleRedirectsFilterQuerystring()
    );
    $field->setHint(
      new PapayaUiStringTranslated(
        'Use captured placeholders for dynamic values: foo={placeholder}&bar=42'
      )
    );
    $group->fields[] = $field = new PapayaUiDialogFieldSelectRadio(
      new PapayaUiStringTranslated('Merge Parameters'),
      'target/qsa',
      new PapayaUiStringTranslatedList(
        array(
          1 => 'Yes',
          0 => 'No'
        )
      )
    );
    $field->setHint(
      new PapayaUiStringTranslated(
        'Append all parameters from the requested url that'.
        ' are not defined by the target query string.'
      )
    );
    $group->fields[] = $field = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Fragment'),
      'target/fragment',
      255,
      '*',
      new PapayaFilterPcre('(^[^#]+$)D')
    );
    $field->setHint(
      new PapayaUiStringTranslated(
        'Use captured placeholders for dynamic values: foo{placeholder}bar'
      )
    );

    $dialog->buttons[] = new PapayaUiDialogButtonSubmit(
      new PapayaUiStringTranslated('Save')
    );

    $this->resetAfterSuccess(TRUE);
    $dialog->callbacks()->onBeforeSave = array($this, 'callbackOnBeforeSave');
    $this->callbacks()->onExecuteSuccessful = array($this, 'callbackExecuteSuccessful');
    return $dialog;
  }

  public function extensions(PapayaContentViewModes $extensions = NULL) {
    if (isset($extensions)) {
      $this->_extensions = new PapayaIteratorMultiple(
        PapayaIteratorMultiple::MIT_KEYS_ASSOC,
        array('*' => new PapayaUiStringTranslated('Any')),
        $extensions
      );
    } elseif (NULL == $this->_extensions) {
      $this->_extensions = new PapayaIteratorMultiple(
        PapayaIteratorMultiple::MIT_KEYS_ASSOC,
        array('*' => new PapayaUiStringTranslated('Any')),
        $extensions = new PapayaContentViewModes()
      );
      $extensions->papaya($this->papaya());
      $extensions->activateLazyLoad();
    }
    return $this->_extensions;
  }

  public function callbackGetExtensionCaption($context, $mode) {
    return is_array($mode) ? PapayaUtilArray::get($mode, 'extension') : (string)$mode;
  }

  public function callbackOnBeforeSave($context, $record) {
    if (!isset($record->priority)) {
      $record->priority = 9999999;
    }
    $targetProperties = array('domain', 'path', 'category_id', 'page_id', 'querystring');
    $targetData = PapayaUtilArray::ensure($record->target);
    foreach ($targetProperties as $name) {
      if (PapayaUtilArray::get($targetData, $name, '') === '') {
        $targetData[$name] = '*';
      }
    }
    $record->target = $targetData;
    return TRUE;
  }

  public function callbackExecuteSuccessful() {
    $this->papaya()->messages->display(
      PapayaMessage::SEVERITY_INFO, new PapayaUiStringTranslated('Redirect rule saved.')
    );
    $this->clipboard()->redirectRule = iterator_to_array($this->record());
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