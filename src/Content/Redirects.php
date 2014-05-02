<?php
/**
* Encapsulation for the redirect database records
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
 * Encapsulation for the redirect database records
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsContentRedirects extends PapayaDatabaseRecordsLazy {

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
   * @var array
   */
  protected $_identifierProperties = array('id');

  /**
   * @var array
   */
  protected $_orderByProperties = array(
    'priority' => PapayaDatabaseInterfaceOrder::ASCENDING,
    'title' => PapayaDatabaseInterfaceOrder::ASCENDING
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

  /**
   * Validate and repair the priority of the rules
   */
  public function validatePriority() {
    $this->lazyLoad();
    $priority = 0;
    $counter = 0;
    foreach ($this as $record) {
      if ($record['priority'] != ++$priority) {
        if ($this->updatePriority($record['id'], $priority)) {
          ++$counter;
        }
      }
    }
    return $counter;
  }

  /**
   * store the priority in the database and change the record if it is loaded.
   *
   * @param integer $id
   * @param integer $priority
   * @return bool
   */
  private function updatePriority($id, $priority) {
    $data = array(
      $this->mapping()->getField('priority') => $priority
    );
    $filter = array(
      $this->mapping()->getField('id') => $id
    );
    $databaseAccess = $this->getDatabaseAccess();
    $updated = $databaseAccess->updateRecord(
      $databaseAccess->getTableName($this->_tableName), $data, $filter
    );
    if (FALSE !== $updated) {
      if (isset($this->_records[$id])) {
        $this->_records[$id]['priority'] = $priority;
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Move the given record in a position before the target record
   *
   * @param integer $id
   * @param integer $idTarget
   * @return bool
   */
  public function moveBefore($id, $idTarget) {
    if (isset($this[$id]) && isset($this[$idTarget])) {
      $databaseAccess = $this->getDatabaseAccess();
      $priorityElement = $this[$id]['priority'];
      $priorityTarget = $this[$idTarget]['priority'];
      if ($priorityElement > $priorityTarget) {
        $sql = 'UPDATE %s
                   SET redirect_priority = redirect_priority + 1
                 WHERE redirect_priority >= %d
                   AND redirect_priority < %d';
        $parameters = array(
          $databaseAccess->getTableName($this->_tableName),
          $priorityTarget,
          $priorityElement
        );
        $newPriority = $priorityTarget;
      } else {
        $sql = 'UPDATE %s
                   SET redirect_priority = redirect_priority - 1
                 WHERE redirect_priority < %d
                   AND redirect_priority > %d';
        $parameters = array(
          $databaseAccess->getTableName($this->_tableName),
          $priorityTarget,
          $priorityElement
        );
        $newPriority = $priorityTarget - 1;
      }
      $updated = FALSE !== $databaseAccess->queryFmtWrite($sql, $parameters);
      if ($updated) {
        $updated = FALSE !== $databaseAccess->updateRecord(
          $databaseAccess->getTableName($this->_tableName),
          array('redirect_priority' => $newPriority),
          array('redirect_id' => $id)
        );
      }
      return $updated;
    }
    return FALSE;
  }

  /**
   * Move the given record in a position after the target record
   *
   * @param integer $id
   * @param integer $idTarget
   * @return bool
   */
  public function moveAfter($id, $idTarget) {
    if (isset($this[$id]) && isset($this[$idTarget])) {
      $databaseAccess = $this->getDatabaseAccess();
      $priorityElement = $this[$id]['priority'];
      $priorityTarget = $this[$idTarget]['priority'];
      if ($priorityElement > $priorityTarget) {
        $sql = 'UPDATE %s
                   SET redirect_priority = redirect_priority + 1
                 WHERE redirect_priority > %d
                   AND redirect_priority < %d';
        $parameters = array(
          $databaseAccess->getTableName($this->_tableName),
          $priorityTarget,
          $priorityElement
        );
        $newPriority = $priorityTarget + 1;
      } else {
        $sql = 'UPDATE %s
                   SET redirect_priority = redirect_priority - 1
                 WHERE redirect_priority <= %d
                   AND redirect_priority > %d';
        $parameters = array(
          $databaseAccess->getTableName($this->_tableName),
          $priorityTarget,
          $priorityElement
        );
        $newPriority = $priorityTarget;
      }
      $updated = FALSE !== $databaseAccess->queryFmtWrite($sql, $parameters);
      if ($updated) {
        $updated = FALSE !== $databaseAccess->updateRecord(
          $databaseAccess->getTableName($this->_tableName),
          array('redirect_priority' => $newPriority),
          array('redirect_id' => $id)
        );
      }
      return $updated;
    }
    return FALSE;
  }
}