<?php
include_once(dirname(__FILE__).'/../bootstrap.php');

class PapayaModuleRedirectsFilterPathTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsFilterPath
   * @dataProvider provideValidPathPatterns
   */
  public function testValidPathPatterns($value) {
    $filter = new PapayaModuleRedirectsFilterPath();
    $this->assertTrue($filter->validate($value));
  }

  /**
   * @covers PapayaModuleRedirectsFilterPath
   * @dataProvider provideInvalidPathPatterns
   */
  public function testInvalidPathPatterns($value) {
    $filter = new PapayaModuleRedirectsFilterPath();
    $this->setExpectedException('PapayaFilterException');
    $filter->validate($value);
  }

  public static function provideValidPathPatterns() {
    return array(
      array('/'),
      array('/foo'),
      array('/bar/bar'),
      array('/foo/{name}'),
      array('/{name}/foo/bar.html'),
      array('/{name}/{name}'),
      array('/{name}/{name}/')
    );
  }
  public static function provideInvalidPathPatterns() {
    return array(
      array(''),
      array('?'),
      array('#'),
      array('/foo?bar')
    );
  }
}