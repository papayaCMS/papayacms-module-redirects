<?php
/**
* Move rules (change priorities) action
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
 * Move rules (change priorities) action
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsAdministrationContentMove
  extends PapayaUiControlCommandAction {

  /**
   * @var PapayaModuleRedirectsContentRedirects
   */
  private $_redirects = NULL;

  protected function _createData(array $definitions = NULL) {
    $validator = parent::_createData(
      array(
        array('id', 0, new PapayaFilterInteger()),
        array('source_id', 0, new PapayaFilterInteger()),
        array('order', 'before', new PapayaFilterList(array('before', 'after'))),
      )
    );
    $this->callbacks()->onValidationSuccessful = array($this, 'callbackChangePriorities');
    return $validator;
  }

  /**
   * Exchange the priority between two rules.
   */
  public function callbackChangePriorities() {
    $data = $this->data();
    if ($data['id'] > 0 &&
        $data['source_id'] > 0 &&
        $data['id'] != $data['source_id']) {
      if ($data['order'] == 'before') {
        $result = $this->redirects()->moveBefore($data['source_id'], $data['id']);
      } else {
        $result = $this->redirects()->moveAfter($data['source_id'], $data['id']);
      }
      if ($result) {
        $this->papaya()->messages->display(
          PapayaMessage::SEVERITY_INFO,
          'Rule priority changed.'
        );
      }
    } else {
      $this->redirects()->validatePriority();
    }
  }

  /**
   * Getter/Setter for a records object containing the redirects
   *
   * @param PapayaModuleRedirectsContentRedirects $redirects
   * @return PapayaModuleRedirectsContentRedirects
   */
  public function redirects(PapayaModuleRedirectsContentRedirects $redirects = NULL) {
    if (isset($redirects)) {
      $this->_redirects = $redirects;
    } elseif (NULL === $this->_redirects) {
      $this->_redirects = new PapayaModuleRedirectsContentRedirects();
      $this->_redirects->papaya($this->papaya());
      $this->_redirects->activateLazyLoad();
    }
    return $this->_redirects;
  }
}
