<?php
include_once(dirname(__FILE__).'/../../bootstrap.php');

class PapayaModuleRedirectsRulePatternPathTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternPath
   * @dataProvider providePatternExamples
   * @param array $expects
   * @param string $pattern
   * @param string $current
   */
  public function testApplyWithValidPath($expects, $pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternPath($pattern, $current);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  /**
   * @covers PapayaModuleRedirectsRulePatternPath
   * @dataProvider provideInvalidPatternExamples
   * @param string $pattern
   * @param string $current
   */
  public function testApplyWithInvalidPaths($pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternPath($pattern, $current);
    $this->assertFalse(
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        array('_path' => '/path'),
        '',
        '/path'
      ),
      array(
        array('_path' => '/path/file.html'),
        '/path/*',
        '/path/file.html'
      ),
      array(
        array('_path' => '/path/file.html'),
        '/*',
        '/path/file.html'
      ),
      array(
        array('file' => 'file.html', '_path' => '/path/file.html'),
        '/path/{file}',
        '/path/file.html'
      ),
      array(
        array('path' => 'path', 'file' => 'file.html', '_path' => '/path/file.html'),
        '/{path}/{file}',
        '/path/file.html'
      )
    );
  }

  public function provideInvalidPatternExamples() {
    return array(
      'different' => array(
        '/foo',
        '/bar'
      ),
      'not enough elements' => array(
        '/foo/*',
        '/bar'
      ),
      'to many elements' => array(
        '/foo',
        '/bar/foo'
      )
    );
  }
}