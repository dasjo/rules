<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\RulesDrupalTestBase.
 */

namespace Drupal\rules\Tests;

use Drupal\rules\Logger\RulesStubLogger;
use Drupal\simpletest\KernelTestBase;

/**
 * Base class for Rules Drupal unit tests.
 */
abstract class RulesDrupalTestBase extends KernelTestBase {

  /**
   * The expression plugin manager.
   *
   * @var \Drupal\rules\Engine\ExpressionPluginManager
   */
  protected $expressionManager;

  /**
   * The condition plugin manager.
   *
   * @var \Drupal\Core\Condition\ConditionManager
   */
  protected $conditionManager;

  /**
   * The typed data manager.
   *
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['rules', 'rules_test', 'system'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Prepare Rules logging for testing.
    $this->installConfig(array('rules'));
    $logger = new RulesStubLogger();
    $this->container->get('config.factory')
      ->getEditable('rules.settings')
      ->set('debug_log', 1)
      ->save();
    $this->container->set('logger', $logger);
    $this->container->get('logger')->cleanLogs();

    $this->expressionManager = $this->container->get('plugin.manager.rules_expression');
    $this->conditionManager = $this->container->get('plugin.manager.condition');
    $this->typedDataManager = $this->container->get('typed_data_manager');
  }

  /**
   * Creates a new condition.
   *
   * @param string $id
   *   The condition plugin id.
   *
   * @return \Drupal\rules\Core\RulesConditionInterface
   *   The created condition plugin.
   */
  protected function createCondition($id) {
    $condition = $this->expressionManager->createInstance('rules_condition', [
      'condition_id' => $id,
    ]);
    return $condition;
  }

  /**
   * Checks if particular message is in the log with given delta.
   *
   * @param string $message
   *   Log message.
   * @param int $log_item_index
   *   Log item's index in log entries stack.
   */
  protected function assertRulesLogEntryExists($message, $log_item_index = 0) {
    // Test that the action has logged something.
    $logs = $this->container->get('logger')->getLogs();
    $this->assertEqual($logs[$log_item_index]['message'], $message);
  }

}
