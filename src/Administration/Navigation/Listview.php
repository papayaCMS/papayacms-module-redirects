<?php
/**
* Navigation listview for administration interface
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
class PapayaModuleRedirectsAdministrationNavigationListview
  extends PapayaUiControlCommand {

  /**
   * @var PapayaUiListview
   */
  private $_listview = NULL;

  /**
   * @var PapayaModuleRedirectsContentRedirects
   */
  private $_redirects = NULL;

  /**
   * Append navigation to parent xml element
   *
   * @param PapayaXmlElement $parent
   * @return PapayaXmlElement
   */
  public function appendTo(PapayaXmlElement $parent) {
    $this->redirects()->validatePriority();
    $parent->append($this->listview());
    return $parent;
  }

  /**
   * Getter/Setter for the navigation listview allowing lazy initalization.
   *
   * @param PapayaUiListview $listview
   * @return PapayaUiListview
   */
  public function listview(PapayaUiListview $listview = NULL) {
    if (isset($listview)) {
      $this->_listview = $listview;
    } elseif (NULL === $this->_listview) {
      $this->_listview = new PapayaUiListview();
      $this->_listview->caption = new PapayaUiStringTranslated('Redirects');
      $this->_listview->builder(
        $builder = new PapayaUiListviewItemsBuilder($this->redirects())
      );
      $this->_listview->builder()->callbacks()->onCreateItem = array($this, 'callbackCreateItem');
      $this->_listview->builder()->callbacks()->onCreateItem->context = $builder;
      $this->_listview->parameterGroup($this->parameterGroup());
      $this->_listview->parameters($this->parameters());
    }
    return $this->_listview;
  }

  /**
   * Callback executed by the listview builder for each record, add an item for the record
   *
   * @param object $context
   * @param PapayaUiListviewItems $items
   * @param array $element
   */
  public function callbackCreateItem($context, $items, $element) {
    $items[] = $item = new PapayaUiListviewItem(
      $element['enabled'] ? 'items-link' : 'status-link-locked',
      $element['title'],
      array(
        'cmd' => 'edit',
        'id' => $element['id']
      )
    );
    if ($element['label'] == PapayaModuleRedirectsContentRedirect::LABEL_IMPORTANT) {
      $item->emphased = TRUE;
    }
    $item->selected = ($element['id'] == $this->parameters()->get('id', 0));
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