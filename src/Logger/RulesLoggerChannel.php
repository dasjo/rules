<?php

/**
 * @file
 * Contains \Drupal\rules\Logger\RulesLoggerChannel.
 */

namespace Drupal\rules\Logger;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Logger\LoggerChannel;
use Psr\Log\LoggerTrait;

/**
 * Logs rules log entries in the available loggers.
 */
class RulesLoggerChannel extends LoggerChannel {
  use LoggerTrait;

  /**
   * A configuration object with rules settings.
   *
   * @var ImmutableConfig
   */
  protected $config;

  /**
   * Creates RulesLoggerChannel object.
   *
   * @param ConfigFactoryInterface $config_factory
   *   Config factory instance.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct('rules');
    $this->config = $config_factory->get('rules.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = []) {
    // Log message only if rules logging setting is enabled.
    if ($this->config->get('debug_log')) {
      if ($this->levelTranslation[$this->config->get('log_errors')] >= $this->levelTranslation[$level]) {
        parent::log($level, $message, $context);
      }
    }
  }

}
