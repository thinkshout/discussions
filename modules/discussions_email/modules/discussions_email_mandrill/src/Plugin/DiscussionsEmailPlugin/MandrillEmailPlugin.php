<?php

namespace Drupal\discussions_email_mandrill\Plugin\DiscussionsEmailPlugin;

use Drupal\comment\Entity\Comment;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManager;
use Drupal\Core\Session\AccountInterface;
use Drupal\discussions\Entity\Discussion;
use Drupal\discussions_email\Plugin\DiscussionsEmailPluginBase;
use Drupal\group\Entity\Group;
use Drupal\mandrill\Plugin\Mail\MandrillMail;
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

  /**
   * {@inheritdoc}
   */
  public function sendEmail($message, Group $group = NULL, Discussion $discussion = NULL, Comment $comment = NULL) {
    $group_email_address = $group->get('discussions_email_address')->value;
    $group_owner_email_address = $group->getOwner()->getEmail();

    $message['params'] = [
      'mandrill' => [
        // Move 'from_email' to suit Mandrill mail plugin.
        'from_email' => $message['from_email'],
        'overrides' => [
          'preserve_recipients' => FALSE,
        ],
      ],
    ];

    // Add Mandrill headers.
    $message['mandrill'] = [
      'header' => [
        // TODO: Add message ID field to comment entity.
        // 'Message-Id' => '',
        'Precedence' => 'List',
        // Mandrill currently only allows List-Help, but this may change.
        'List-Help' => "<mailto:{$group_owner_email_address}>",
        'List-Unsubscribe:' => "<mailto:{$group_email_address}?subject=unsubscribe>",
        'List-Post:' => "<mailto:{$group_email_address}>",
        'List-Owner:' => "<mailto:{$group_owner_email_address}>",
      ],
    ];

    // Concatenate recipient email addresses for Mandrill mail plugin.
    $message['to'] = implode(',', $message['to']);

    // 'module' value must be set to avoid error in Mandrill mail plugin.
    $message['module'] = 'discussions_email';

    /** @var MailManager $mail_manager */
    $mail_manager = \Drupal::service('plugin.manager.mail');

    /** @var MandrillMail $mandrill */
    $mandrill = $mail_manager->createInstance('mandrill_mail');

    // Format and send mail through Mandrill.
    $message = $mandrill->format($message);
    return $mandrill->mail($message);
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
      \Drupal::logger('discussions_email_mandrill')->error('Unable to process message; no user found with email {email}', [
        'email' => $message['from_email'],
      ]);
      return FALSE;
    }

    // Load discussion group from group email address.
    /** @var Group $group */
    $group = $this->loadGroupFromEmail($message['email']);

    if (empty($group)) {
      \Drupal::logger('discussions_email_mandrill')->error('Unable to process message; no group found for email {email}', [
        'email' => $message['email'],
      ]);
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
        \Drupal::logger('discussions_email_mandrill')->error('Unable to process message; no discussion with ID {discussion_id} found in group with ID {group_id}', [
          'discussion_id' => $discussion_id,
          'group_id' => $group->id(),
        ]);
        return FALSE;
      }

      // Add new comment to discussion.
      $filtered_message = $this->filterEmailReply($message['html']);

      $group_discussion_service->addComment($discussion->id(), $parent_comment_id, $user->id(), $filtered_message);
    }
    else {
      // Create new discussion.
      $filtered_message = $this->filterEmailReply($message['html']);
      $this->createNewDiscussion($user, $group, $message['subject'], $filtered_message);
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
