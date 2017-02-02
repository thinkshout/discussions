<?php

namespace Drupal\discussions\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\discussions\DiscussionTypeInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Defines the Discussion Type entity.
 *
 * @ConfigEntityType(
 *   id = "discussion_type",
 *   label = @Translation("Discussion Type"),
 *   handlers = {
 *     "list_builder" = "Drupal\discussions\Controller\DiscussionTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\discussions\Form\DiscussionTypeForm",
 *       "edit" = "Drupal\discussions\Form\DiscussionTypeForm",
 *       "delete" = "Drupal\discussions\Form\DiscussionTypeDeleteForm"
 *     }
 *   },
 *   config_prefix = "discussion_type",
 *   admin_permission = "administer discussions",
 *   bundle_of = "discussion",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/discussions/discussion_type/{discussion_type}",
 *     "add-form" = "/admin/structure/discussions/discussion_type/add",
 *     "edit-form" = "/admin/structure/discussions/discussion_type/{discussion_type}/edit",
 *     "delete-form" = "/admin/structure/discussions/discussion_type/{discussion_type}/delete",
 *     "collection" = "/admin/structure/discussions/discussion_type"
 *   }
 * )
 */
class DiscussionType extends ConfigEntityBundleBase implements DiscussionTypeInterface {

  /**
   * The Discussion Type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Discussion Type label.
   *
   * @var string
   */
  protected $label;

}
