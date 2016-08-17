<?php

namespace Drupal\discussions\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\discussions\DiscussionGroupMemberInterface;

/**
 * Defines the Discussion Group Member entity.
 *
 * @ingroup discussion
 *
 * @ContentEntityType(
 *   id = "discussion_group_member",
 *   label = @Translation("Discussion Group Member"),
 *   base_table = "discussion_group_members",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class DiscussionGroupMember extends ContentEntityBase implements DiscussionGroupMemberInterface {

}
