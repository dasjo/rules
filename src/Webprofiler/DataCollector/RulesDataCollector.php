<?php

/**
 * @file
 * Contains \Drupal\webprofiler\DataCollector\AssetDataCollector.
 */

namespace Drupal\rules\Webprofiler\DataCollector;

use Drupal\webprofiler\DataCollector\DrupalDataCollectorTrait;
use Drupal\webprofiler\DrupalDataCollectorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Drupal\rules\Webprofiler\RulesChannelLoggerWrapper;

/**
 * Collects data about the used assets (CSS/JS).
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
   * Constructs a AssetDataCollector object.
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
   * {@inheritdoc}
   */
  public function getPanel() {
    $build = array();

    $build['table_title'] = array(
      '#type' => 'inline_template',
      '#template' => '<h3>Rules logs</h3>',
    );

    $cssHeader = array(
      'level',
      'message',
      'passed_context',
    );

    $rows = array();
    foreach ($this->data['logs'] as $log) {
      $row = array();

      $row[] = $log['level'];
      $row[] = $log['message'];
      $row[] = implode(', ', array_keys($log['context']));

      $rows[] = $row;
    }

    $build['logs_table'] = array(
      '#type' => 'table',
      '#rows' => $rows,
      '#header' => $cssHeader,
      '#sticky' => TRUE,
    );

    return $build;
  }

}
