<?php

namespace Drupal\discussions\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\discussions\DiscussionInterface;

/**
 * Defines the Discussion entity.
 *
 * @ingroup discussion
 *
 * @ContentEntityType(
 *   id = "discussion",
 *   label = @Translation("Discussion"),
 *   bundle_label = @Translation("Discussion Type"),
 *   handlers = {
 *     "form" = {
 *       "default" = "Drupal\discussions\Form\DiscussionForm",
 *       "add" = "Drupal\discussions\Form\DiscussionForm",
 *       "edit" = "Drupal\discussions\Form\DiscussionForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     }
 *   },
 *   base_table = "discussions",
 *   admin_permission = "administer discussions",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "bundle" = "type"
 *   },
 *   links = {
 *     "canonical" = "/discussions/{group}/{discussion_group}",
 *     "add-form" = "/discussions/{group}/add"
 *   },
 *   bundle_entity_type = "discussion_type",
 *   field_ui_base_route = "entity.discussion_type.edit-form"
 * )
 */
class Discussion extends ContentEntityBase implements DiscussionInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'user');

    $fields['subject'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Subject'))
      ->setDescription(t('The Discussion subject.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'settings' => array(
          'display_label' => TRUE,
        ),
        'weight' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['body'] = BaseFieldDefinition::create('text')
      ->setLabel(t('Body'))
      ->setDescription(t('The Discussion text.'))
      ->setSettings(array(
        'default_value' => '',
        'text_processing' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'text',
        'settings' => array(
          'display_label' => TRUE,
        ),
        'weight' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'text',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['private'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Private Discussion'))
      ->setDescription(t('A boolean indicating whether the Discussion is private, regardless of Discussion Group privacy status.'))
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
