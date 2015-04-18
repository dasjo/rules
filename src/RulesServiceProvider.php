<?php

/**
 * @file
 * Contains \Drupal\rules\RulesServiceProvider.
 */

namespace Drupal\rules;

use Drupal\Core\Config\BootstrapConfigStorageFactory;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Defines a service profiler for the WebProfiler module.
 */
class RulesServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    if (FALSE !== $container->hasDefinition('logger.channel.rules') && $container->hasDefinition('webprofiler.drupal')) {
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

}
