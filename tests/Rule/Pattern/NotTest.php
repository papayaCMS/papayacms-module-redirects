<?php
include_once(dirname(__FILE__).'/../../bootstrap.php');

class PapayaModuleRedirectsRulePatternNotTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternNot
   * @dataProvider providePatternExamples
   * @param mixed $expects
   * @param PapayaModuleRedirectsRulePattern $condition
   * @param PapayaModuleRedirectsRulePattern $subrule
   */
  public function testApply($expects, $condition, $subrule) {
    $rule = new PapayaModuleRedirectsRulePatternNot($condition, $subrule);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        TRUE,
        $this->getRuleMock(FALSE),
        $this->getRuleMock(TRUE)
      ),
      array(
        FALSE,
        $this->getRuleMock(TRUE),
        $this->getRuleMock(FALSE)
      ),
      array(
        FALSE,
        $this->getRuleMock(TRUE),
        $this->getRuleMock(TRUE)
      ),
      array(
        FALSE,
        $this->getRuleMock(array()),
        $this->getRuleMock(TRUE)
      ),
      array(
        array('foo' => 'bar'),
        $this->getRuleMock(FALSE),
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