<?php

/**
 * @file
 * Contains \Drupal\rules_webprofiler_test\EventSubscriber
 */

namespace Drupal\rules_webprofiler_test\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RulesWebProfilerTestSubscriber implements EventSubscriberInterface {

  public function executeRulesAction(GetResponseEvent $event) {
    $manager = \Drupal::service('plugin.manager.rules_expression');
    $action = $manager->createAction('rules_test_webprofiler_log');
    $action->execute();
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('executeRulesAction');
    return $events;
  }

}
