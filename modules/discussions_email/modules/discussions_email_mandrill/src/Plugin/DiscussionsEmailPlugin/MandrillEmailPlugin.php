<?php

namespace Drupal\discussions_email_mandrill\Plugin\DiscussionsEmailPlugin;

use Drupal\Component\Serialization\Json;
use Drupal\discussions_email\Plugin\DiscussionsEmailPluginBase;
use Drupal\user\Entity\User;
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
    $user = user_load_by_mail($message['from_email']);

    if (empty($user)) {
      // TODO: Log error message.
      return FALSE;
    }

    // Load discussion group from group email address.
    $group = $this->loadGroupFromEmail($message['email']);

    if (empty($group)) {
      // TODO: Log error message.
      return FALSE;
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
