<?php

namespace Drupal\discussions_email\Plugin;

use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\discussions\Entity\Discussion;
use Drupal\group\Entity\Group;
use Drupal\group\Entity\GroupContent;

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
  public function processBounce(Group $group, $email) {

  }

  /**
   * {@inheritdoc}
   */
  public function processUnsubscribe(Group $group, $email) {
    $user = user_load_by_mail($email);

    if (!empty($user)) {
      $group_member = $group->getMember($user);

      if (!empty($group_member)) {
        $group_member->getGroupContent()->delete();
      }
    }
  }

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
    $filter_css_classes = $config->get('filter_css_classes');
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
   * Creates a new discussion in a group.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user creating the discussion.
   * @param \Drupal\group\Entity\Group $group
   *   The group to create the discussion in.
   * @param $subject
   *   The discussion subject.
   * @param $body
   *   The discussion body text.
   */
  public function createNewDiscussion(AccountInterface $user, Group $group, $subject, $body) {
    // Get first enabled group_discussion plugin to create discussion
    // group content.
    // TODO: Allow a way to indicate which plugin to use from email address?
    /** @var \Drupal\group\Plugin\GroupContentEnablerInterface $default_plugin */
    $default_plugin = NULL;
    /** @var \Drupal\group\Plugin\GroupContentEnablerInterface $plugin */
    foreach ($group->getGroupType()->getInstalledContentPlugins() as $plugin_id => $plugin) {
      if ($plugin->getBaseId() == 'group_discussion') {
        $default_plugin = $plugin;
        break;
      }
    }

    list($plugin_type, $discussion_type) = explode(':', $default_plugin->getPluginId());

    $discussion = Discussion::create([
      'type' => $discussion_type,
      'uid' => $user->id(),
      'subject' => $subject,
    ]);

    if ($discussion->save() == SAVED_NEW) {
      $group_content = GroupContent::create([
        'type' => $default_plugin->getContentTypeConfigId(),
        'gid' => $group->id(),
      ]);

      $group_content->set('entity_id', $discussion->id());
      $group_content->save();

      // Add initial comment to new discussion.

      /** @var \Drupal\discussions\GroupDiscussionService $group_discussion_service */
      $group_discussion_service = \Drupal::service('discussions.group_discussion');
      $group_discussion_service->addComment($discussion->id(), 0, $user->id(), $body);
    }
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
