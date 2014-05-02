<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternPageTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternPage
   * @dataProvider providePatternExamples
   * @param array $expects
   * @param array $pattern
   * @param string $current
   */
  public function testApplyWithValidPage($expects, $pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternPage(
      $pattern,
      new PapayaUrl($current)
    );
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  /**
   * @covers PapayaModuleRedirectsRulePatternPage
   * @dataProvider provideInvalidPatternExamples
   * @param array $pattern
   * @param string $current
   */
  public function testApplyWithInvalidPage($pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternPage(
      $pattern,
      new PapayaUrl($current)
    );
    $this->assertFalse(
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        array(
          '_page_id' => 42,
          '_category_id' => 0,
          '_extension' => 'html',
          '_language' => ''
        ),
        array(),
        '/index.42.html'
      ),
      array(
        array(
          '_page_id' => 42,
          '_category_id' => 21,
          '_extension' => 'html',
          '_language' => 'de'
        ),
        array(),
        '/index.21.42.de.html'
      ),
      array(
        array(
          '_page_id' => 42,
          '_category_id' => 21,
          '_extension' => 'html',
          '_language' => 'de'
        ),
        array(
          'page_id' => 42
        ),
        '/index.21.42.de.html'
      ),
      array(
        array(
          '_page_id' => 42,
          '_category_id' => 21,
          '_extension' => 'html',
          '_language' => 'de'
        ),
        array(
          'page_id' => 42,
          'category_id' => 21
        ),
        '/index.21.42.de.html'
      ),
      array(
        array(
          '_page_id' => 42,
          '_category_id' => 21,
          '_extension' => 'atom',
          '_language' => 'en'
        ),
        array(
          'page_id' => 42,
          'category_id' => 21,
          'extension' => 'atom'
        ),
        '/index.21.42.en.atom'
      ),
      array(
        array(
          '_page_id' => 42,
          '_category_id' => 21,
          '_extension' => 'atom',
          '_language' => 'en'
        ),
        array(
          'page_id' => 42,
          'category_id' => 21,
          'language' => 'en',
          'extension' => 'atom'
        ),
        '/index.21.42.en.atom'
      )
    );
  }

  public function provideInvalidPatternExamples() {
    return array(
      'no page' => array(
        array(),
        '/bar'
      ),
      'page id not in range' => array(
        array('page_id' => '1-21'),
        '/index.42.html'
      ),
      'wrong category id' => array(
        array('category_id' => '23'),
        '/index.21.42.html'
      )
    );
  }
}