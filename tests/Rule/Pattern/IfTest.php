<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternIfTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternIf
   * @dataProvider providePatternExamples
   * @param mixed $expects
   * @param PapayaModuleRedirectsRulePattern $condition
   * @param PapayaModuleRedirectsRulePattern $subrule
   */
  public function testApply($expects, $condition, $subrule) {
    $rule = new PapayaModuleRedirectsRulePatternIf($condition, $subrule);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        FALSE,
        $this->getRuleMock(FALSE),
        $this->getRuleMock(TRUE)
      ),
      array(
        FALSE,
        $this->getRuleMock(TRUE),
        $this->getRuleMock(FALSE)
      ),
      array(
        TRUE,
        $this->getRuleMock(TRUE),
        $this->getRuleMock(TRUE)
      ),
      array(
        TRUE,
        $this->getRuleMock(array()),
        $this->getRuleMock(TRUE)
      ),
      array(
        array('foo' => 'bar'),
        $this->getRuleMock(TRUE),
        $this->getRuleMock(array('foo' => 'bar'))
      )
    );
  }

  public function getRuleMock($data = FALSE) {
    $stub = $this->getMock('PapayaModuleRedirectsRulePattern');
    $stub
      ->expects($this->any())
      ->method('apply')
      ->will($this->returnValue($data));
    return $stub;
  }
}