<?php

namespace Drupal\discussions\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\discussions\DiscussionGroupInterface;

/**
 * Defines the Discussion Group entity.
 *
 * @ingroup discussion
 *
 * @ConfigEntityType(
 *   id = "discussion_group",
 *   label = @Translation("Discussion Group"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\discussions\Form\DiscussionGroupForm",
 *       "edit" = "Drupal\discussions\Form\DiscussionGroupForm",
 *       "delete" = "Drupal\discussions\Form\DiscussionGroupDeleteForm"
 *     }
 *   },
 *   admin_permission = "administer discussion groups",
 *   config_prefix = "discussion_group",
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/discussions/group/{discussion_group}",
 *     "delete-form" = "/admin/config/discussions/group/{discussion_group}/delete"
 *   }
 * )
 */
class DiscussionGroup extends ConfigEntityBundleBase implements DiscussionGroupInterface {

  /**
   * The machine name of the Discussion Group.
   *
   * @var string
   */
  public $id;

  /**
   * The Discussion Group title.
   *
   * @var string
   */
  public $title;

  /**
   * The Discussion Group description.
   *
   * @var string
   */
  public $description;

  /**
   * The privacy status of the Discussion Group.
   *
   * @var boolean
   */
  public $private;

}
