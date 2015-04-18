<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\RulesWebProfilerTest
 */

namespace Drupal\rules\Tests;

/**
 * Class RulesWebProfilerTest
 *
 * Tests integration Rules logging with WebProfiler module.
 *
 * @group rules
 */
class RulesWebProfilerTest extends RulesDrupalWebTestBase {

  /**
   * Authenticated user with access to WebProfiler.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $webProfilerUser;

  public static $modules = ['rules', 'webprofiler', 'block'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setup();

    // Enable rules logging.
    $this->container->get('config.factory')
      ->getEditable('rules.settings')
      ->set('debug_log', 1)
      ->set('log_errors', 'debug')
      ->save();

    $this->webProfilerUser = $this->drupalCreateUser(array(
      'access webprofiler',
      'view webprofiler toolbar',
    ));

    // Enables rules web debugging with WebProfiler.
    $this->container->get('config.factory')
      ->getEditable('webprofiler.config')
      ->set('active_toolbar_items.rules', 'rules')
      ->save();

    $this->drupalLogin($this->webProfilerUser);
  }

  /**
   * Goes to WebProfiler page using link from toolbar and check entries there.
   */
  public function testWebProfilerPage() {
    $this->drupalGet('404', [
      'query' => [
        'log' => '1',
        'log-level' => 'critical',
        'log-message' => 'critical message',
        'log-amount' => 5,
      ],
    ]);

    $this->drupalGet('admin/reports/profiler/list');
    $links = $this->xpath('//main//table[1]//a');
    $url = $this->getAbsoluteUrl($links[0]['href']);
    $this->drupalGet($url);
    $this->assertText('Rules logs', 'Rules logs table exists');
    $this->assertText('critical message', 'Rules log entry exists');
  }

}
