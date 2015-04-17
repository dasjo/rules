<?php

/**
 * @file
 * Contains \Drupal\rules\WebProfiler\WebProfilerServiceProvider.
 */

namespace Drupal\rules;

use Drupal\Core\Config\BootstrapConfigStorageFactory;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

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
      $container->register('webprofiler.rules', 'Drupal\rules\WebProfiler\DataCollector\RulesDataCollector')
        ->addArgument(new Reference('logger.channel.rules'))
        ->addTag('data_collector', array(
          'template' => '@rules/Collector/rules.html.twig',
          'id' => 'rules',
          'title' => 'Rules',
          'priority' => 200,
        ));
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
