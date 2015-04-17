<?php

/**
 * @file
 * Contains Drupal\rules\WebProfiler\RulesChannelLoggerWrapper
 */

namespace Drupal\rules\WebProfiler;

use Drupal\rules\Logger\RulesChannelLogger;

class RulesChannelLoggerWrapper extends RulesChannelLogger {

  /**
   * Static list of rules log entries.
   *
   * @var array
   */
  protected $logs;

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    parent::log($level, $message, $context);

    $this->logs[] = array(
      'level' => $level,
      'message' => $message,
      'context' => $context,
    );
  }

  /**
   * Return a list of rules log entries.
   *
   * @return array
   *   List of rules log entries.
   */
  public function getLogs() {
    return $this->logs;
  }

}
