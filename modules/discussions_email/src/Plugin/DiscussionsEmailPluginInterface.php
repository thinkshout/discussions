<?php

namespace Drupal\discussions\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines the interface for discussions email plugins.
 *
 * @see \Drupal\filter\Annotation\Filter
 * @see \Drupal\filter\FilterPluginManager
 * @see \Drupal\filter\Plugin\FilterBase
 * @see plugin_api
 */
interface DiscussionsEmailPluginInterface extends ConfigurablePluginInterface, PluginInspectionInterface {

}
