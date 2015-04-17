<?php

/**
 * @file
 * Contains Drupal\rules\WebProfiler\RulesChannelLoggerWrapper
 */

namespace Drupal\rules\WebProfiler;

use Drupal\rules\Logger\RulesLoggerChannel;

class RulesChannelLoggerWrapper extends RulesLoggerChannel {

  /**
   * Static list of rules log entries.
   *
   * @var array
   */
  protected $logs = [
    [
      'level' => 'info',
      'message' => 'Hello',
      'context' => []
    ],
    [
      'level' => 'critical',
      'message' => 'Take care!',
      'context' => []
    ]
  ];

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array()) {
    parent::log($level, $message, $context);

    $this->logs[] = [
      'level' => $level,
      'message' => $message,
      'context' => $context,
    ];
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
