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
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\discussions\Form\DiscussionGroupMemberForm",
 *       "edit" = "Drupal\discussions\Form\DiscussionGroupMemberForm",
 *       "delete" = "Drupal\discussions\Form\DiscussionGroupMemberDeleteForm",
 *     }
 *   },
 *   base_table = "discussion_group_members",
 *   admin_permission = "administer discussion group members",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/discussions/group/{discussion_group}/member/{discussion_group_member}",
 *     "add-form" = "/admin/config/discussions/group/{discussion_group}/member/add",
 *     "edit-form" = "/admin/config/discussions/group/{discussion_group}/member/{discussion_group_member}/edit",
 *     "delete-form" = "/admin/config/discussions/group/{discussion_group}/member/{discussion_group_member}/delete",
 *   },
 * )
 */
class DiscussionGroupMember extends ContentEntityBase implements DiscussionGroupMemberInterface {

}
