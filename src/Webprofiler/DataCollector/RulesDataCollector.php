<?php

/**
 * @file
 * Contains \Drupal\rules\WebProfiler\DataCollector\RulesDataCollector.
 */

namespace Drupal\rules\WebProfiler\DataCollector;

use Drupal\webprofiler\DataCollector\DrupalDataCollectorTrait;
use Drupal\webprofiler\DrupalDataCollectorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Drupal\rules\WebProfiler\RulesChannelLoggerWrapper;

/**
 * Collects Rules module log entries.
 */
class RulesDataCollector extends DataCollector implements DrupalDataCollectorInterface {

  use StringTranslationTrait, DrupalDataCollectorTrait;

  /**
   * The rules logger wrapper.
   *
   * @var RulesChannelLoggerWrapper
   */
  private $rulesLogger;

  /**
   * Constructs a RulesDataCollector object.
   *
   * @param RulesChannelLoggerWrapper $rulesLogger
   *   The rules logger channel.
   */
  public function __construct(RulesChannelLoggerWrapper $rulesLogger) {
    $this->rulesLogger = $rulesLogger;
  }

  /**
   * {@inheritdoc}
   */
  public function collect(Request $request, Response $response, \Exception $exception = NULL) {
    $this->data['logs'] = $this->rulesLogger->getLogs();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'rules';
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->t('Rules');
  }

  /**
   * {@inheritdoc}
   */
  public function getPanelSummary() {
    return $this->t('Total rules log entries: @count', array('@count' => $this->getLogsCount()));
  }

  /**
   * Return amount of rules log entries.
   *
   * @return int
   *   Amount of rules log entries.
   */
  public function getLogsCount() {
    return count($this->data['logs']);
  }

  /**
   * Return amount of rules log entries with level higher then warning.
   *
   * @return int
   *   Amount of error rules log entries.
   */
  public function getErrorLogsCount() {
    $amount = 0;
    array_walk($this->data['logs'], function ($log) use (&$amount) {
      if (in_array($log['level'], array('error', 'critical', 'alert', 'emergency'))) {
        $amount++;
      }
    });

    return $amount;
  }

  /**
   * Return amount of rules log entries with level notice or warning.
   *
   * @return int
   *   Amount of error rules log entries.
   */
  public function getNoticeLogsCount() {
    $amount = 0;
    array_walk($this->data['logs'], function ($log) use (&$amount) {
      if (in_array($log['level'], array('warning', 'notice'))) {
        $amount++;
      }
    });

    return $amount;
  }

  /**
   * Return amount of rules info log entries.
   *
   * @return int
   *   Amount of error rules log entries.
   */
  public function getInfoLogsCount() {
    $amount = 0;
    array_walk($this->data['logs'], function ($log) use (&$amount) {
      if (in_array($log['level'], array('debug', 'info'))) {
        $amount++;
      }
    });

    return $amount;
  }

  /**
   * {@inheritdoc}
   */
  public function getPanel() {
    $build = array();

    $build['table_title'] = array(
      '#type' => 'inline_template',
      '#template' => '<h3>{{ "Rules logs"|t }}</h3>',
    );

    $cssHeader = array(
      'level',
      'message',
      'passed_context',
    );

    $rows = array_map(function ($log) {
      return [
        $log['level'], $log['message'], implode(', ', array_keys($log['context']))
      ];
    }, $this->data['logs']);

    $build['logs_table'] = array(
      '#type' => 'table',
      '#rows' => $rows,
      '#header' => $cssHeader,
      '#sticky' => TRUE,
    );

    return $build;
  }

}
