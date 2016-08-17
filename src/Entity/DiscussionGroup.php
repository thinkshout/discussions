<?php

namespace Drupal\discussions\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Discussion Group entity.
 *
 * @ingroup discussion
 *
 * @ConfigEntityType(
 *   id = "discussion_group",
 *   label = @Translation("Discussion Group"),
 *   config_prefix = "discussion_group",
 *   entity_keys = {
 *     "id" = "id"
 *   }
 * )
 */
class DiscussionGroup extends ConfigEntityBundleBase implements DiscussionGroupInterface {

}
