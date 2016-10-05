<?php

namespace Drupal\discussions_email\Plugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\group\Entity\Group;

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

    list($email_username, $discussion_id, $parent_comment_id) = explode(self::DISCUSSION_GROUP_EMAIL_SEPARATOR, $email_parts[0]);

    $group_email = $email_username . '@' . $email_parts[1];

    // Load group using group email.
    $group_ids = \Drupal::entityQuery('group')
      ->condition('discussions_email_address', $group_email, '=')
      ->execute();

    if (!empty($group_ids)) {
      $group_id = current(array_keys($group_ids));

      return Group::load($group_id);
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function filterEmailReply($message) {
    $reply_line_position = strpos($message, DISCUSSIONS_EMAIL_MESSAGE_SEPARATOR);

    // Filter out html tags in message.
    $config = \Drupal::config('discussions_email.settings');
    $filter_css_classes = $config->get('discussions_email_filter_css_classes');
    $classes_array = explode(',', $filter_css_classes);

    // Loop through CSS classes to filter out div elements.
    if (!empty($classes_array)) {
      foreach ($classes_array as $class) {
        $div_tag = '<div class="' . trim($class) . '">';

        // Get position of dev element.
        $tag_pos = strpos($message, $div_tag);
        if ($tag_pos !== FALSE) {

          $reply_line_position = min($reply_line_position, $tag_pos);
        }
      }

      $prior_close_tag_position = strrpos($message, '</', $reply_line_position - strlen($message));
      $next_open_tag_position = strpos($message, '<', $prior_close_tag_position + 2);

      $message = substr($message, 0, $next_open_tag_position);
    }

    return $message;
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
  public function calculateDependencies() {
    return [];
  }

}
