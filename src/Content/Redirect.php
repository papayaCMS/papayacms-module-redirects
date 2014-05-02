<?php
/**
* Encapsulation for the redirect database record
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
 * Encapsulation for the redirect database record
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 *
 * @property int $id
 * @property int $priority
 * @property string $label
 * @property int $status
 * @property bool $enabled
 * @property string $title
 * @property array $pattern
 * @property array $target
 */
class PapayaModuleRedirectsContentRedirect extends PapayaDatabaseRecordLazy {

  const MODE_URL = 'url';
  const MODE_PAGE = 'page';

  const LABEL_NONE = 0;
  const LABEL_IMPORTANT = 1;

  /**
   * @var array
   */
  protected $_fields = array(
    'id' => 'redirect_id',
    'priority' => 'redirect_priority',
    'label' => 'redirect_label',
    'status' => 'redirect_status',
    'enabled' => 'redirect_enabled',
    'title' => 'redirect_title',
    'pattern' => 'redirect_pattern',
    'target' => 'redirect_target'
  );

  /**
   * @var string
   */
  protected $_tableName = PapayaModuleRedirectsContentTables::REDIRECTS;

  /**
   * Override mapping to encode/decode the pattern and target array values
   *
   * @return PapayaDatabaseRecordMapping
   */
  public function _createMapping() {
    $mapping = parent::_createMapping();
    $mapping->callbacks()->onMapValueFromFieldToProperty = array(
      $this, 'callbackMapValueFromFieldToProperty'
    );
    $mapping->callbacks()->onMapValueFromPropertyToField = array(
      $this, 'callbackMapValueFromPropertyToField'
    );
    return $mapping;
  }

  /**
   * @param object $context
   * @param string $property
   * @param string $field
   * @param string $value
   * @return mixed
   */
  public function callbackMapValueFromFieldToProperty($context, $property, $field, $value) {
    switch ($property) {
    case 'pattern' :
    case 'target' :
      return PapayaUtilStringXml::unserializeArray($value);
    }
    return $value;
  }

  /**
   * @param object $context
   * @param string $property
   * @param string $field
   * @param mixed $value
   * @return string
   */
  public function callbackMapValueFromPropertyToField($context, $property, $field, $value) {
    switch ($property) {
    case 'pattern' :
    case 'target' :
      return PapayaUtilStringXml::serializeArray($value);
    }
    return $value;
  }
}