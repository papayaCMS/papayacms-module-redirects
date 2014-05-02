<?php
/**
* Creates a rule structure for an papaya page url, depending on the pattern data.
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
 * Creates a rule structure for an papaya page url, depending on the pattern data.
 *
 * @package Papaya-Modules
 * @subpackage Free-Redirects
 */
class PapayaModuleRedirectsRulePatternPage extends PapayaModuleRedirectsRulePatternCallback {

  /**
   * @var array
   */
  private $_data = array();

  /**
   * @var PapayaUrl
   */
  private $_url = NULL;

  /**
   * @var PapayaRequestParserPage
   */
  private $_parser = NULL;

  public function __construct(array $data, PapayaUrl $url) {
    parent::__construct(array($this, 'callbackCreateRule'));
    $this->_data = $data;
    $this->_url = $url;
  }

  public function parser(PapayaRequestParserPage $parser = NULL) {
    if (isset($parser)) {
      $this->_parser = $parser;
    } elseif (NULL === $this->_parser) {
      $this->_parser = new PapayaRequestParserPage();
    }
    return $this->_parser;
  }

  public function callbackCreateRule() {
    if ($current = $this->parser()->parse($this->_url)) {
      $rules = new PapayaModuleRedirectsRulePatternAnd(
        new PapayaModuleRedirectsRulePatternRange(
          PapayaUtilArray::get($this->_data, 'page_id', ''),
          PapayaUtilArray::get($current, 'page_id', 0),
          '_page_id'
        ),
        new PapayaModuleRedirectsRulePatternRange(
          PapayaUtilArray::get($this->_data, 'category_id', ''),
          PapayaUtilArray::get($current, 'category_id', 0),
          '_category_id'
        )
      );
      $extension = PapayaUtilArray::get($this->_data, 'extension', '');
      if ($extension && $extension != '*') {
        $rules->add(
          new PapayaModuleRedirectsRulePatternEquals(
            $extension,
            PapayaUtilArray::get($current, 'output_mode', ''),
            '_extension'
          )
        );
      } else {
        $rules->add(
          new PapayaModuleRedirectsRulePatternValue(
            array('_extension' => PapayaUtilArray::get($current, 'output_mode', ''))
          )
        );
      }
      $language = PapayaUtilArray::get($this->_data, 'language', '');
      if ($language && $language != '*') {
        $rules->add(
          new PapayaModuleRedirectsRulePatternEquals(
            $language,
            PapayaUtilArray::get($current, 'language', ''),
            '_language'
          )
        );
      } else {
        $rules->add(
          new PapayaModuleRedirectsRulePatternValue(
            array('_language' => PapayaUtilArray::get($current, 'language', ''))
          )
        );
      }
      return $rules;
    }
    return FALSE;
  }
}