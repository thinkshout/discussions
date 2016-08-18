<?php

namespace Drupal\discussions\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
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
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
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

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['discussion_group'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Discussion Group'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'discussion_group');

    $fields['user'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pending'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Pending Approval'))
      ->setDescription(t('A boolean indicating whether the Discussion Group Member is pending approval.'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'settings' => array(
          'display_label' => TRUE,
        ),
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the Discussion Group Member was created.'))
      ->setRevisionable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the Discussion Group Member was last edited.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

}
