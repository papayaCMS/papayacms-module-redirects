<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternOrTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternOr
   * @dataProvider providePatternExamples
   * @param mixed $expects
   * @param array(PapayaModuleRedirectsRulePattern) $rules
   */
  public function testApply($expects, $rules) {
    $rule = new PapayaModuleRedirectsRulePatternOr();
    foreach ($rules as $subrule) {
      $rule->add($subrule);
    }
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        FALSE,
        array()
      ),
      array(
        TRUE,
        array($this->getRuleMock(TRUE))
      ),
      array(
        TRUE,
        array(
          $this->getRuleMock(FALSE),
          $this->getRuleMock(TRUE)
        )
      ),
      array(
        array('foo' => 'bar'),
        array(
          $this->getRuleMock(FALSE),
          $this->getRuleMock(TRUE),
          $this->getRuleMock(array('foo' => 'bar')),
        )
      ),
      array(
        array('bar' => 'foo', 'foo' => 'bar'),
        array(
          $this->getRuleMock(FALSE),
          $this->getRuleMock(array('bar' => 'foo')),
          $this->getRuleMock(TRUE),
          $this->getRuleMock(array('foo' => 'bar')),
        )
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