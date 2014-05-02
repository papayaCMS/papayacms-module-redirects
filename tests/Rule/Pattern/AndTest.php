<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternAndTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternAnd
   * @dataProvider providePatternExamples
   * @param mixed $expects
   * @param array(PapayaModuleRedirectsRulePattern) $rules
   */
  public function testApply($expects, $rules) {
    $rule = new PapayaModuleRedirectsRulePatternAnd();
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
          $this->getRuleMock(TRUE),
          $this->getRuleMock(TRUE)
        )
      ),
      array(
        FALSE,
        array(
          $this->getRuleMock(FALSE),
          $this->getRuleMock(TRUE)
        )
      ),
      array(
        FALSE,
        array(
          $this->getRuleMock(TRUE),
          $this->getRuleMock(FALSE)
        )
      ),
      array(
        array('foo' => 'bar'),
        array(
          $this->getRuleMock(TRUE),
          $this->getRuleMock(array('foo' => 'bar')),
        )
      ),
      array(
        FALSE,
        array(
          $this->getRuleMock(TRUE),
          $this->getRuleMock(array('foo' => 'bar')),
          $this->getRuleMock(FALSE)
        )
      ),
      array(
        array('bar' => 'foo', 'foo' => 'bar'),
        array(
          $this->getRuleMock(array('bar' => 'foo')),
          $this->getRuleMock(TRUE),
          $this->getRuleMock(array('foo' => 'bar')),
        )
      ),
      array(
        FALSE,
        array(
          $this->getRuleMock(array('bar' => 'foo')),
          $this->getRuleMock(TRUE),
          $this->getRuleMock(array('foo' => 'bar')),
          $this->getRuleMock(FALSE),
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