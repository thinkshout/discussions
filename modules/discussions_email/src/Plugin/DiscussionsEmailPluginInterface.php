<?php

namespace Drupal\discussions_email\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\discussions\Entity\Discussion;
use Drupal\group\Entity\Group;

/**
 * Defines the interface for discussions email plugins.
 *
 * @see \Drupal\discussions_email\Annotation\DiscussionsEmailPlugin
 * @see \Drupal\discussions_email\DiscussionsEmailPluginManager
 * @see \Drupal\discussions_email\Plugin\DiscussionsEmailPluginBase
 * @see plugin_api
 */
interface DiscussionsEmailPluginInterface extends ConfigurablePluginInterface, PluginInspectionInterface {

  /**
   * Gets an array of inbound domains from the email provider.
   *
   * @return array
   *
   * TODO: Standardize format for response between plugins.
   */
  public function getInboundDomains();

  /**
   * Validates the source of an update sent to the webhook endpoint.
   *
   * @return bool
   *   TRUE if the source is valid, FALSE otherwise.
   */
  public function validateWebhookSource();

  /**
   * Process data received from the email provider via a webhook update.
   *
   * @param array $data
   *   Data received via webhook request.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   HTTP response object.
   */
  public function processWebhook($data);

  /**
   * Loads a discussion group using the group's email address.
   *
   * @param $email
   *   An email address in the format:
   *   {string}+{int}+{int}@domain.tld
   *     - Group email username (string)
   *     - Discussion ID (int) (optional)
   *     - Parent comment ID (int) (optional)
   *
   * @return \Drupal\group\Entity\GroupInterface
   *   The group object.
   */
  public function loadGroupFromEmail($email);

  /**
   * Removes HTML markup from email messages based on group configuration.
   *
   * @param string $message
   *   The original email message.
   *
   * @return string
   *   The filtered email message.
   *
   */
  public function filterEmailReply($message);

  /**
   * Sends an email message.
   *
   * @param string $from_address
   *   The sender email address.
   * @param array $to_addresses
   *   Array of recipient email addresses.
   * @param string $body
   *   The email body.
   * @param \Drupal\group\Entity\Group $group
   *   The discussion group containing the recipients.
   * @param \Drupal\discussions\Entity\Discussion $discussion
   *   The discussion the email message originated from.
   */
  public function sendEmail($from_address, $to_addresses, $body, Group $group = NULL, Discussion $discussion = NULL);

}
