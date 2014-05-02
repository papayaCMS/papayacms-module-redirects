<?php
include_once(dirname(__FILE__).'/../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsFilterRangeTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsFilterRange
   * @dataProvider provideValidRangePatterns
   */
  public function testValidRangePatterns($value) {
    $filter = new PapayaModuleRedirectsFilterRange();
    $this->assertTrue($filter->validate($value));
  }

  /**
   * @covers PapayaModuleRedirectsFilterRange
   * @dataProvider provideInvalidRangePatterns
   */
  public function testInvalidRangePatterns($value) {
    $filter = new PapayaModuleRedirectsFilterRange();
    $this->setExpectedException('PapayaFilterException');
    $filter->validate($value);
  }

  public static function provideValidRangePatterns() {
    return array(
      array('1'),
      array('1,2'),
      array('1,4-5'),
      array('4-5,42,84')
    );
  }
  public static function provideInvalidRangePatterns() {
    return array(
      array(''),
      array('a')
    );
  }
}