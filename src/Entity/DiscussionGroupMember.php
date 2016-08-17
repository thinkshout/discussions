<?php

namespace Drupal\discussion\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Discussion Group Member entity.
 *
 * @ingroup discussion
 *
 * @ConfigEntityType(
 *   id = "discussion_group_member",
 *   label = @Translation("Discussion Group Member"),
 *   config_prefix = "discussion_group_member",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   }
 * )
 */
class DiscussionGroupMember extends ConfigEntityBundleBase implements DiscussionGroupMemberInterface {

}
