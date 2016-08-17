<?php

namespace Drupal\discussion\Entity;

use Drupal\Core\Entity\ContentEntityBase;

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
