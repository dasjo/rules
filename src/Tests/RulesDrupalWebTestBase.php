<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\RulesDrupalWebTestBase
 */

namespace Drupal\rules\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests that the webprofile shows rules debug log and respects rules settings.
 *
 * @group block
 */
class RulesDrupalWebTestBase extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['rules', 'rules_ui'];

  /**
   * Authenticated user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * User with administer rules permissions.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $permissions = array('create page content', 'administer rules');
    $this->user = $this->drupalCreateUser();
    $this->adminUser = $this->drupalCreateUser($permissions);
  }

}
