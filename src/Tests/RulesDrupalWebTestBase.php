<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\RulesDrupalWebTestBase
 */

namespace Drupal\rules\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Rules base web test.
 *
 * @group rules
 */
abstract class RulesDrupalWebTestBase extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['rules'];

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
    // @todo uncomment it when patch with permission comes.
    // $this->adminUser = $this->drupalCreateUser($permissions);
  }

}
