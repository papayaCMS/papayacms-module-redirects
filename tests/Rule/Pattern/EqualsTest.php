<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternEqualsTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternEquals
   * @dataProvider providePatternExamples
   * @param mixed $expects
   * @param string $currentValue
   * @param array $values
   * @param null|string $name
   */
  public function testApplyTo($expects, $currentValue, $values, $name = NULL) {
    $rule = new PapayaModuleRedirectsRulePatternEquals($values, $currentValue, $name);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  public static function providePatternExamples() {
    return array(
      array(FALSE, '', array()),
      array(FALSE, 'FooBar', array('Foo', 'Bar')),
      array(TRUE, 'Foo', array('Foo', 'Bar')),
      array(TRUE, 'Foo', 'Foo'),
      array(array('parameter' => 'Foo'), 'Foo', array('Foo', 'Bar'), 'parameter')
    );
  }
}