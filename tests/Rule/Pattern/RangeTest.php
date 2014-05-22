<?php
include_once(dirname(__FILE__).'/../../bootstrap.php');

class PapayaModuleRedirectsRulePatternRangeTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternRange
   * @dataProvider providePatternExamples
   * @param array $expects
   * @param string $pattern
   * @param string $current
   * @param string $name
   */
  public function testApplyWithValidRange($expects, $pattern, $current, $name = '') {
    $rule = new PapayaModuleRedirectsRulePatternRange($pattern, $current, $name);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  /**
   * @covers PapayaModuleRedirectsRulePatternRange
   * @dataProvider provideInvalidPatternExamples
   * @param string $pattern
   * @param string $current
   */
  public function testApplyWithInvalidRanges($pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternRange($pattern, $current);
    $this->assertFalse(
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      'equals' => array(
        TRUE,
        '42',
        '42'
      ),
      'equals 2' => array(
        TRUE,
        '21',
        '21'
      ),
      'in list' => array(
        TRUE,
        '21,42,84',
        '42'
      ),
      'in range' => array(
        TRUE,
        '21,40-44,84',
        '42'
      ),
      'equals, named' => array(
        array('_id' => 42),
        '42',
        '42',
        '_id'
      ),
      'in list, named' => array(
        array('page_id' => 42),
        '21,42,84',
        '42',
        'page_id'
      ),
      'in range, named' => array(
        array('_page_id' => 42),
        '21,40-44,84',
        '42',
        '_page_id'
      )
    );
  }

  public function provideInvalidPatternExamples() {
    return array(
      'different' => array(
        '1',
        '2'
      ),
      'not in list' => array(
        '4',
        '2,3,5,6'
      ),
      'to small' => array(
        '1',
        '4-10'
      ),
      'to large' => array(
        '100',
        '4-90'
      ),
      'between ranges' => array(
        '50',
        '1-49,51-100'
      )
    );
  }
}