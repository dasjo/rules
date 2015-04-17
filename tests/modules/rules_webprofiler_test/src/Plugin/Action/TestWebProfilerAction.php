<?php

/**
 * @file
 * Contains \Drupal\rules_webprofiler_test\Plugin\Action\TestWebProfilerAction.
 */

namespace Drupal\rules_webprofiler_test\Plugin\Action;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\rules\Core\RulesActionBase;
use Drupal\rules\Logger\RulesLoggerChannel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides an action writing something to the Rules log.
 *
 * @Action(
 *   id = "rules_test_webprofiler_log",
 *   label = @Translation("Test webprofiler action.")
 * )
 */
class TestWebProfilerAction extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * Rules logger instance.
   *
   * @var \Drupal\rules\Logger\RulesLoggerChannel
   */
  protected $logger;

  /**
   * Rules logger instance.
   *
   * @var Request
   */
  protected $request;

  /**
   * Constructs a TestLogAction object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\rules\Logger\RulesLoggerChannel $logger
   *   Rules logger object.
   * @param RequestStack $request_stack
   *   Symfony request stack service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RulesLoggerChannel $logger, RequestStack $request_stack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger;
    $this->request = $request_stack->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.channel.rules'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    // Get level passed via GET param.
    if (!$log = $this->request->get('log', 'enabled')) {
      return;
    }
    $level = $this->request->get('log-level', 'debug');
    $amount = $this->request->get('log-amount', 1);
    $message = $this->request->get('log-message', 'debug message');
    $contexts = $this->request->get('log-contexts', '');
    for ($i = 0; $i < $amount; $i++) {
      $this->logger->log($level, $message . '#' . $i, array_flip(explode('|', $contexts)));
    }
  }

}
