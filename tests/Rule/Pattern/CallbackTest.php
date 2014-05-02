<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternCallbackTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternCallback
   * @dataProvider providePatternExamples
   * @param mixed $expects
   * @param callable $callback
   */
  public function testApply($expects, $callback) {
    $rule = new PapayaModuleRedirectsRulePatternCallback($callback);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        TRUE,
        array($this, 'callbackReturnTrue')
      ),
      array(
        FALSE,
        array($this, 'callbackReturnFalse')
      ),
      array(
        array('foo' => 'bar'),
        array($this, 'callbackReturnArray')
      ),
      array(
        array('bar' => 'foo'),
        array($this, 'callbackReturnRule')
      ),
      array(
        TRUE,
        array($this, 'callbackReturnStdClassTriggerCasting')
      )
    );
  }

  public function callbackReturnTrue() {
    return TRUE;
  }

  public function callbackReturnFalse() {
    return FALSE;
  }

  public function callbackReturnArray() {
    return array('foo' => 'bar');
  }

  public function callbackReturnRule() {
    return $this->getRuleMock(array('bar' => 'foo'));
  }

  public function callbackReturnStdClassTriggerCasting() {
    return new stdClass();
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