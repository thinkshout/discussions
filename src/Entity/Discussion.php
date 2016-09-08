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
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\discussions\Controller\DiscussionListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\discussions\Form\DiscussionForm",
 *       "add" = "Drupal\discussions\Form\DiscussionForm",
 *       "edit" = "Drupal\discussions\Form\DiscussionForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "add-to-group" = "Drupal\discussions\Form\GroupDiscussionForm"
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
 *     "canonical" = "/discussion/{discussion}",
 *   },
 *   bundle_entity_type = "discussion_type",
 *   field_ui_base_route = "entity.discussion_type.edit_form"
 * )
 */
class Discussion extends ContentEntityBase implements DiscussionInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->subject->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('User ID'))
      ->setDescription(t('The user ID of the discussion creator.'))
      ->setSetting('unsigned', TRUE);

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

    $fields['comments'] = BaseFieldDefinition::create('comment')
      ->setLabel(t('Comments'))
      ->setDescription(t('Replies to the Discussion.'))
      ->setSettings(array(
        'comment_type' => 'discussions_reply',
        'default_mode' => 1,
        'per_page' => 50,
        'anonymous' => 0,
        'form_location' => TRUE,
        'preview' => 0,
      ))
      ->setDefaultValue(array(
        'status' => 2,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'comment',
        'weight' => 0,
      ))
      ->setDisplayOptions('view', array(
        'type' => 'comment',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the Discussion was created.'))
      ->setRevisionable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the Discussion was last edited.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

}
