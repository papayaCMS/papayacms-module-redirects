<?php
include_once(dirname(__FILE__).'/../../../../../../bootstrap.php');
PapayaTestCase::registerPapayaAutoloader(
  array(
    'PapayaModuleRedirects' => 'modules/free/redirects'
  )
);

class PapayaModuleRedirectsRulePatternHostTest extends PapayaTestCase {

  /**
   * @covers PapayaModuleRedirectsRulePatternHost
   * @dataProvider providePatternExamples
   * @param array $expects
   * @param string $pattern
   * @param string $current
   */
  public function testApplyWithValidHosts($expects, $pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternHost($pattern, $current);
    $this->assertEquals(
      $expects,
      $rule->apply()
    );
  }

  /**
   * @covers PapayaModuleRedirectsRulePatternHost
   * @dataProvider provideInvalidPatternExamples
   * @param string $pattern
   * @param string $current
   */
  public function testApplyWithInvalidHosts($pattern, $current) {
    $rule = new PapayaModuleRedirectsRulePatternHost($pattern, $current);
    $this->assertFalse(
      $rule->apply()
    );
  }

  public function providePatternExamples() {
    return array(
      array(
        array('_host' => 'localhost'),
        '',
        'localhost'
      ),
      array(
        array('_host' => 'localhost'),
        '*',
        'localhost'
      ),
      array(
        array('_host' => 'host.tld'),
        '*',
        'host.tld'
      ),
      array(
        array('_host' => 'host.tld'),
        'host.*',
        'host.tld'
      ),
      array(
        array('_host' => 'www.host.tld'),
        '*.host.*',
        'www.host.tld'
      ),
      array(
        array('_host' => 'themes.www.host.tld'),
        '*.host.*',
        'themes.www.host.tld'
      ),
      array(
        array(
          'tld' => 'de',
          '_host' => 'themes.www.host.de'
        ),
        '*.host.{tld}',
        'themes.www.host.de'
      ),
      array(
        array(
          'tld' => 'de',
          '_host' => 'www.host.de'
        ),
        '*.{tld}',
        'www.host.de'
      ),
      array(
        array(
          'tld' => 'de',
          'subdomain' => 'www',
          '_host' => 'themes.www.host.de'
        ),
        '*.{subdomain}.host.{tld}',
        'themes.www.host.de'
      )
    );
  }

  public function provideInvalidPatternExamples() {
    return array(
      'different' => array(
        'localhost',
        'host.tld'
      ),
      'tld mismatch' => array(
        'host.de',
        'host.tld'
      ),
      'subdomain expected' => array(
        '*.host.de',
        'host.tld'
      ),
      'host expected' => array(
        '*.{foo}',
        'host'
      )
    );
  }
}