<?php

namespace Drupal\discussions_email\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines the interface for discussions email plugins.
 *
 * @see \Drupal\discussions_email\Annotation\DiscussionsEmailPlugin
 * @see \Drupal\discussions_email\DiscussionsEmailPluginManager
 * @see \Drupal\discussions_email\Plugin\DiscussionsEmailPluginBase
 * @see plugin_api
 */
interface DiscussionsEmailPluginInterface extends ConfigurablePluginInterface, PluginInspectionInterface {

  /**
   * Validates the source of an update sent to the webhook endpoint.
   *
   * @return bool
   *   TRUE if the source is valid, FALSE otherwise.
   */
  public function validateSource();

  /**
   * Process data received from email provider via a webhook update.
   *
   * @param array $data
   *   Data received via webhook request.
   */
  public function processWebhook($data);

}
