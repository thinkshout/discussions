<?php

namespace Drupal\discussions\Plugin;

use Drupal\Core\Plugin\PluginBase;

/**
 * Provides a base class for discussions email plugins.
 *
 * @see \Drupal\discussions_email\DiscussionsEmailPluginManager
 * @see \Drupal\discussions_email\Plugin\DiscussionsEmailPluginInterface
 * @see plugin_api
 */
abstract class DiscussionsEmailPluginBase extends PluginBase implements DiscussionsEmailPluginInterface {

}
