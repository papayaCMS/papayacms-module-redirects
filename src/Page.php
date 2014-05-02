<?php
/**
* 404 Error page, with redirects
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
 * 404 Error page, with redirects. An error page, that can output informations about
 * an request error. Configured as PAPAYA_PAGEID_ERROR_404 it allows to match the
 * requested url, apply rules and redirect to another url
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsPage
  extends PapayaObject
  implements PapayaPluginAppendable, PapayaPluginEditable {

  /**
   * @var null
   */
  private $_content = NULL;

  /**
   * @var PapayaModuleRedirectsContentRedirects
   */
  private $_redirects;

  public function appendTo(PapayaXmlElement $parent = NULL) {
    if ($data = $this->getRule()) {
      $reference = $this->getTarget($data['redirect'], $data['placeholders'])->getReference();
      /**
       * @var PapayaResponse $response
       */
      $response = $this->papaya()->response;
      $response->setStatus(
        PapayaUtilArray::get($data['redirect'], 'status', 301)
      );
      $response->headers()->set('Location', $reference->get());
      $response->send();
      $response->end();
    } else {
      $parent->appendElement('title', array(), $this->content()->get('title', ''));
      $parent->appendElement('text')->appendXml($this->content()->get('text', ''));
      if ($error = $this->getError()) {
        $parent->appendElement(
          'papaya-error',
          array('status' => $error['status'], 'code' => $error['code']),
          $error['message']
        );
      } elseif ($this->papaya()->request->isPreview) {
        $parent->appendElement(
          'papaya-error',
          array('status' => 0, 'code' => 0),
          'Preview Sample'
        );
      }
    }
  }

  /**
   * The content is an {@see ArrayObject} containing the stored data.
   *
   * @see PapayaPluginEditable::content()
   * @param PapayaPluginEditableContent $content
   * @return PapayaPluginEditableContent
   */
  public function content(PapayaPluginEditableContent $content = NULL) {
    if (isset($content)) {
      $this->_content = $content;
    } elseif (NULL == $this->_content) {
      $this->_content = new PapayaPluginEditableContent();
      $this->_content->callbacks()->onCreateEditor = array($this, 'createEditor');
    }
    return $this->_content;
  }

  /**
   * The editor is used to change the stored data in the administration interface.
   *
   * In this case it the editor creates an dialog from a field definition.
   *
   * @see PapayaPluginEditableContent::editor()
   *
   * @param $callbackContext
   * @param PapayaPluginEditableContent $content
   * @return PapayaPluginEditor
   */
  public function createEditor($callbackContext, PapayaPluginEditableContent $content) {
    $editor = new PapayaAdministrationPluginEditorFields(
      $content,
      array(
        'title' => array(
          'caption' => new PapayaUiStringTranslated('Title'),
          'mandatory' => TRUE,
          'type' => 'input',
          'parameters' => 400
        ),
        'text' => array(
          'caption' => new PapayaUiStringTranslated('Text'),
          'type' => 'richtext',
          'parameters' => 20
        )
      )
    );
    $editor->papaya($this->papaya());
    return $editor;
  }

  private function getRule() {
    $url = $this->papaya()->request->getUrl();
    foreach ($this->redirects() as $redirect) {
      $rules = new PapayaModuleRedirectsRulePatternUrl(
        PapayaUtilArray::ensure($redirect['pattern']),
        $url
      );
      if (FALSE !== ($data = $rules->apply())) {
        return array(
          'redirect' => $redirect,
          'placeholders' => $data
        );
      }
    }
    return NULL;
  }

  private function getTarget(array $redirect, $placeholders) {
    $target = new PapayaModuleRedirectsRuleMatch(
      PapayaUtilArray::ensure($redirect['target']),
      $placeholders
    );
    $target->papaya($this->papaya());
    return $target;
  }

  private function getError() {
    if (isset($GLOBALS['PAPAYA_PAGE']) &&
        is_object($GLOBALS['PAPAYA_PAGE'])) {
      return empty($GLOBALS['PAPAYA_PAGE']->error) ? NULL : $GLOBALS['PAPAYA_PAGE']->error;
    }
    return NULL;
  }

  public function redirects(PapayaModuleRedirectsContentRedirects $redirects = NULL) {
    if (isset($redirects)) {
      $this->_redirects = $redirects;
    } elseif (NULL == $this->_redirects) {
      $this->_redirects = new PapayaModuleRedirectsContentRedirects();
      $this->_redirects->papaya($this->papaya());
      $this->_redirects->activateLazyLoad(array('enabled' => TRUE));
    }
    return $this->_redirects;
  }
}