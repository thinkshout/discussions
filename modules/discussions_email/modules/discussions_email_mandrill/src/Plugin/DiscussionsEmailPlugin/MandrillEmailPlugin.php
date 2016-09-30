<?php

namespace Drupal\discussions_email_mandrill\Plugin\DiscussionsEmailPlugin;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Session\AccountInterface;
use Drupal\discussions\Entity\Discussion;
use Drupal\discussions_email\Plugin\DiscussionsEmailPluginBase;
use Drupal\group\Entity\Group;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a content enabler for users.
 *
 * @DiscussionsEmailPlugin(
 *   id = "discussions_email_mandrill",
 *   label = @Translation("Mandrill Email Plugin for Discussions"),
 *   description = @Translation("Allows discussions to be sent via email using Mandrill.")
 * )
 */
class MandrillEmailPlugin extends DiscussionsEmailPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getInboundDomains() {
    /** @var \Drupal\mandrill\MandrillAPI $api */
    $api = \Drupal::service('mandrill.api');

    return $api->getInboundDomains();
  }

  /**
   * {@inheritdoc}
   */
  public function validateWebhookSource() {
    // TODO: Validation.
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function processWebhook($data) {
    // Return an empty response if the webhook is being verified.
    if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
      return Response::create();
    }

    // Get Mandrill events from webhook data.
    $events = Json::decode($_POST['mandrill_events']);

    // Process Mandrill events.
    foreach ($events as $event) {
      switch ($event['event']) {
        case 'hard_bounce':
        case 'reject':
          $this->processBounce($event['msg']);
          break;
        case 'inbound':
          $this->processMessage($event['msg']);
          break;
      }
    }

    return Response::create(count($events) . ' events processed.');
  }

  protected function processBounce($message) {

  }

  /**
   * @param array $message
   *   Associative array of message information.
   *   - email (string): The recipient email address in the format:
   *     {string}+{int}+{int}@domain.tld
   *       - Group email username (string)
   *       - Discussion ID (int) (optional)
   *       - Parent comment ID (int) (optional)
   *
   * @return bool
   *   TRUE if message was successfully processed, FALSE otherwise.
   */
  protected function processMessage($message) {
    // TODO: Ignore messages sent from the discussions_email module.

    // Load user using the message sender's email address.
    /** @var AccountInterface $user */
    $user = user_load_by_mail($message['from_email']);

    if (empty($user)) {
      // TODO: Log error message.
      return FALSE;
    }

    // Load discussion group from group email address.
    /** @var Group $group */
    $group = $this->loadGroupFromEmail($message['email']);

    if (empty($group)) {
      // TODO: Log error message.
      return FALSE;
    }

    // Check user is a member of the group.
    if (!$group->getMember($user)) {
      return FALSE;
    }

    // TODO: Check user permission to create reply to discussion.
    // TODO: Can group / posting access be done in DiscussionsEmailPluginBase?

    $email_parts = explode('@', $message['email']);

    list($email_username, $discussion_id, $parent_comment_id) = explode(self::DISCUSSION_GROUP_EMAIL_SEPARATOR, $email_parts[0]);

    if (!empty($discussion_id)) {
      // Load discussion.

      /** @var \Drupal\discussions\GroupDiscussionService $group_discussion_service */
      $group_discussion_service = \Drupal::service('discussions.group_discussion');

      $discussion = $group_discussion_service->getGroupDiscussion($group->id(), $discussion_id);

      if (empty($discussion)) {
        // TODO: Log error message.
        return FALSE;
      }

      // Add new comment to discussion.
      $filtered_message = $this->filterEmailReply($message['html']);

      // TODO: Handle replies to existing comments.

      $group_discussion_service->addComment($discussion->id(), $user->id(), $filtered_message);
    }
    else {
      // TODO: Create new discussion.
    }
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    // TODO: Any Mandrill plugin configuration.
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    // TODO: Any Mandrill plugin configuration fields.
    return $form;
  }

}
