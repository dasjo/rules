<?php

/**
 * @file
 * Contains \Drupal\rules\Webprofiler\WebprofilerServiceProvider.
 */

namespace Drupal\rules\Webprofiler;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Defines a service profiler for the webprofiler module.
 */
class WebprofilerServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    // Replace the regular logger.channel.rules service
    // with a traceable one.
    $definition = $container->findDefinition('logger.channel.rules');
    $definition->setClass('Drupal\rules\Webprofiler\RulesChannelLoggerWrapper');
  }
}
