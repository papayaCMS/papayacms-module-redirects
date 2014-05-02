<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternSchemeTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternScheme
   * @dataProvider providePatternExamples
   * @param array $expects
   * @param integer $mode
   * @param string $current
   */
  public function testApply($expects, $mode, $current) {
    $rule = new PapayaModuleRedirectsRulePatternScheme($mode, $current);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        array('_scheme' => 'http'),
        PapayaUtilServerProtocol::HTTP,
        'http'
      )
    );
  }
}