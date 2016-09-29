<?php

namespace Drupal\discussions_email_mandrill\Plugin\DiscussionsEmailPlugin;

use Drupal\discussions_email\Plugin\DiscussionsEmailPluginBase;
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
      return Response::create('');
    }

    // Get Mandrill events from webhook data.
    $events = $data['mandrill_events'];

    // TODO: Process events.

    return Response::create(1);
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
