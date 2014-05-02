<?php
include_once(dirname(__FILE__).'/../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsFilterHostTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsFilterHost
   * @dataProvider provideValidHostPatterns
   */
  public function testValidHostPatterns($value) {
    $filter = new PapayaModuleRedirectsFilterHost();
    $this->assertTrue($filter->validate($value));
  }

  /**
   * @covers PapayaModuleRedirectsFilterHost
   * @dataProvider provideInvalidHostPatterns
   */
  public function testInvalidHostPatterns($value) {
    $filter = new PapayaModuleRedirectsFilterHost();
    $this->setExpectedException('PapayaFilterException');
    $filter->validate($value);
  }

  public static function provideValidHostPatterns() {
    return array(
      array('foo'),
      array('foo.bar'),
      array('*'),
      array('*.foo'),
      array('foo.*'),
      array('*.foo.bar')
    );
  }
  public static function provideInvalidHostPatterns() {
    return array(
      array('')
    );
  }
}