<?php

/**
 * @file
 * Contains \Drupal\rules\Logger\RulesStubLogger.
 */

namespace Drupal\rules\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;

/**
 * Logs events into the memory with ability check it afterwards.
 */
class RulesStubLogger implements LoggerInterface {
  use RfcLoggerTrait;

  /**
   * The database connection object.
   *
   * @var array
   */
  protected $logs;

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    $this->logs[] = [
      'level' => $level,
      'message' => $message,
      'context' => $context,
    ];
  }

  /**
   * Clears static logs storage.
   */
  public function cleanLogs() {
    $this->logs = array();
  }

  /**
   * Returns statically saved logs.
   *
   * @return array
   *   Array of logs.
   */
  public function getLogs() {
    return $this->logs;
  }

}
