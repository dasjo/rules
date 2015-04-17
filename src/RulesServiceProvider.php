<?php

/**
 * @file
 * Contains \Drupal\rules\WebProfiler\WebProfilerServiceProvider.
 */

namespace Drupal\rules;

use Drupal\Core\Config\BootstrapConfigStorageFactory;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Defines a service profiler for the web profiler module.
 */
class RulesServiceProvider extends ServiceProviderBase {

  const CONFIG_PREFIX = 'webprofiler.config';

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    if (FALSE !== $container->hasDefinition('logger.channel.rules') && $this->isRulesDebuggingEnabled()) {
      // Replace the regular logger.channel.rules service with a traceable one.
      $definition = $container->findDefinition('logger.channel.rules');
      $definition->setClass('Drupal\rules\WebProfiler\RulesChannelLoggerWrapper');
    }
  }

  /**
   * Checks whether the site is multilingual.
   *
   * @return bool
   *   TRUE if the site is multilingual, FALSE otherwise.
   */
  protected function isRulesDebuggingEnabled() {
    $config_storage = BootstrapConfigStorageFactory::get();
    $config = $config_storage->read(static::CONFIG_PREFIX);
    return !empty($config['active_toolbar_items']['rules']);
  }

}
