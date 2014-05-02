<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternValueTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternValue
   * @dataProvider providePatternExamples
   * @param mixed $expects
   * @param mixed $value
   */
  public function testApply($expects, $value) {
    $rule = new PapayaModuleRedirectsRulePatternValue($value);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        TRUE,
        TRUE
      ),
      array(
        TRUE,
        'string'
      ),
      array(
        array('foo' => 'bar'),
        array('foo' => 'bar'),
      ),
      array(
        FALSE,
        FALSE
      ),
      array(
        FALSE,
        0
      )
    );
  }
}