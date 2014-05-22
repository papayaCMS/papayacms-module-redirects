<?php
include_once(dirname(__FILE__).'/../bootstrap.php');

class PapayaModuleRedirectsFilterQuerystringTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsFilterQuerystring
   * @dataProvider provideValidQuerystringPatterns
   */
  public function testValidQuerystringPatterns($value) {
    $filter = new PapayaModuleRedirectsFilterQuerystring();
    $this->assertTrue($filter->validate($value));
  }

  /**
   * @covers PapayaModuleRedirectsFilterQuerystring
   * @dataProvider provideInvalidQuerystringPatterns
   */
  public function testInvalidQuerystringPatterns($value) {
    $filter = new PapayaModuleRedirectsFilterQuerystring();
    $this->setExpectedException('PapayaFilterException');
    $filter->validate($value);
  }

  public static function provideValidQuerystringPatterns() {
    return array(
      array('foo'),
      array('foo={bar}'),
      array('foobar&foo=bar')
    );
  }
  public static function provideInvalidQuerystringPatterns() {
    return array(
      array('')
    );
  }
}