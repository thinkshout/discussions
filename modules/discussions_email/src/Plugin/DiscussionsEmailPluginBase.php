<?php

namespace Drupal\discussions_email\Plugin;

use Drupal\Core\Plugin\PluginBase;

/**
 * Provides a base class for discussions email plugins.
 *
 * @see \Drupal\discussions_email\DiscussionsEmailPluginManager
 * @see \Drupal\discussions_email\Plugin\DiscussionsEmailPluginInterface
 * @see plugin_api
 */
abstract class DiscussionsEmailPluginBase extends PluginBase implements DiscussionsEmailPluginInterface {

  const DISCUSSION_GROUP_EMAIL_SEPARATOR = '+';

  /**
   * {@inheritdoc}
   */
  public function loadGroupFromEmail($email) {
    $email_parts = explode('@', $email);

    list($group_name, $discussion_id, $parent_comment_id) = explode(self::DISCUSSION_GROUP_EMAIL_SEPARATOR, $email_parts[0]);

    $group_email = $group_name . '@' . $email_parts[1];

    // TODO: Load group using group email.
  }

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
