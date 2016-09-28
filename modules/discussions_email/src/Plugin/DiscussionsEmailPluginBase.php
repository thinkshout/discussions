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

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    // Merge in the default configuration.
    $this->configuration = NestedArray::mergeDeep(
      $this->defaultConfiguration(),
      $configuration
    );

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    // TODO: Any email plugin configuration.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // TODO: Any email plugin configuration fields.
    $form = [];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

}
