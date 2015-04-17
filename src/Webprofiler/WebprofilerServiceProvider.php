<?php

/**
 * @file
 * Contains \Drupal\rules\WebProfiler\WebprofilerServiceProvider.
 */

namespace Drupal\rules\WebProfiler;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Defines a service profiler for the web profiler module.
 */
class WebProfilerServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    // Replace the regular logger.channel.rules service with a traceable one.
    $definition = $container->findDefinition('logger.channel.rules');
    $definition->setClass('Drupal\rules\WebProfiler\RulesChannelLoggerWrapper');
  }
}
