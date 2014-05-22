<?php
include_once(dirname(__FILE__).'/../../bootstrap.php');

class PapayaModuleRedirectsRulePatternQuerystringTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternQuerystring
   * @dataProvider providePatternExamples
   * @param array $expects
   * @param string $pattern
   * @param string $current
   */
  public function testApplyWithValidQuerystring($expects, $pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternQuerystring($pattern, $current);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  /**
   * @covers PapayaModuleRedirectsRulePatternQuerystring
   * @dataProvider provideInvalidPatternExamples
   * @param string $pattern
   * @param string $current
   */
  public function testApplyWithInvalidQuerystrings($pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternQuerystring($pattern, $current);
    $this->assertFalse(
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        array(
          '_querystring' => 'foo=1'
        ),
        'foo=*',
        'foo=1'
      ),
      array(
        array(
          '_querystring' => 'foo=42',
          'named' => '42'
        ),
        'foo={named}',
        'foo=42'
      ),
      array(
        array(
          '_querystring' => 'bar=21&foo=42',
          'named' => '42'
        ),
        'foo={named}',
        'bar=21&foo=42'
      ),
      array(
        array(
          '_querystring' => 'foo/bar=42',
          'named' => '42'
        ),
        'foo[bar]={named}',
        'foo/bar=42'
      )
    );
  }

  public function provideInvalidPatternExamples() {
    return array(
      'missing' => array(
        'foo=*',
        'bar=1'
      ),
      'missing 2' => array(
        'foo={value}',
        'bar=1'
      ),
    );
  }
}