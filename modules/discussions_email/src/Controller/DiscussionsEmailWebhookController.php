<?php

namespace Drupal\discussions_email\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for discussions email webhook.
 *
 * @ingroup discussions_email
 */
class DiscussionsEmailWebhookController extends ControllerBase {

  /**
   * Webhook endpoint to process updates from email providers.
   */
  public function endpoint() {
    // TODO: Load current discussions email plugin.
    // TODO: Process update through plugin.

    return Response::create(1);
  }

}
