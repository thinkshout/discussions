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

}
