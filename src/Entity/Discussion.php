<?php

namespace Drupal\discussion\Entity;

use Drupal\Core\Entity\ContentEntityBase;

/**
 * Defines the Discussion entity.
 *
 * @ingroup discussion
 *
 * @ContentEntityType(
 *   id = "discussion",
 *   label = @Translation("Discussion"),
 *   base_table = "discussions",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class Discussion extends ContentEntityBase implements DiscussionInterface {

}
