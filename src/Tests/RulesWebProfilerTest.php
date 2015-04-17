<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\RulesWebProfilerTest
 */

namespace Drupal\rules\Tests;

/**
 * Class RulesWebProfilerTest
 * @group Rules
 */
class RulesWebProfilerTest extends RulesDrupalWebTestBase {

  /**
   * Authenticated user with access to web profiler.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $webProfilerUser;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setup();

    $this->webProfilerUser = $this->drupalCreateUser(array(
      'access webprofiler',
      'view webprofiler toolbar',
    ));

    // Enables rules web debugging with web profiler.
    $this->config('webprofiler.config')->set('active_toolbar_items.rules', 'rules');
    $this->drupalLogin($this->webProfilerUser);
  }

  /**
   * Tests does necessary information exist in WebProfiler toolbar.
   */
  public function testWebProfilerToolbar() {
    $this->drupalGet('<front>', array(
      'log' => '1',
      'log-level' => 'info',
      'log-message' => 'info message',
      'log-amount' => 5,
    ));

    $this->assertText('Rules logs', 'Rules logs are visible in the toolbar.');
    $this->assertText('Info log entries 5', 'Additional rules logs info is visible in the toolbar.');

    $this->drupalGet('<front>', array(
      'log' => '1',
      'log-level' => 'critical',
      'log-message' => 'critical message',
      'log-amount' => 3,
    ));

    $this->assertText('Rules logs', 'Rules logs are visible in the toolbar.');
    $this->assertText('Error log entries 3', 'Additional rules logs info is visible in the toolbar.');

    $this->drupalGet('<front>', array(
      'log' => '0',
      'log-level' => 'debug',
      'log-message' => 'debug message',
      'log-amount' => 3,
    ));

    $this->assertText('Rules logs', 'Rules logs are visible in the toolbar.');
    $this->assertText('Debug log entries 0', 'Additional rules logs info is visible in the toolbar.');

    $this->config('webprofiler.config')->set('active_toolbar_items.rules', '');

    $this->drupalGet('<front>', array(
      'log' => '1',
      'log-level' => 'debug',
      'log-message' => 'debug message',
      'log-amount' => 3,
    ));

    $this->assertNoText('Rules logs', 'Rules logs are no visible in the toolbar.');
    $this->assertNoText('Debug log entries 3', 'Additional rules logs info is not visible in the toolbar.');
  }

  /**
   * Goes to WebProfiler page using link from toolbar and check entries there.
   */
  public function testWebProfilerPage() {
    $this->drupalGet('<front>', array(
      'log' => '1',
      'log-level' => 'info',
      'log-message' => 'info message',
      'log-amount' => 5,
    ));

    $links = $this->xpath('//div[@class="sf-toolbar-icon"]/a[@title="Rules"]');

    $url = $this->getAbsoluteUrl($links[0]['href']);
    $this->drupalGet($url);
    $this->assertText('Rules logs', 'Rules logs table exists');
    $this->assertText('info message', 'Rules log entry exists');
  }

}
