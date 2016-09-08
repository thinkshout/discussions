<?php

namespace Drupal\discussions\Form;

use Drupal\comment\Entity\Comment;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\group\Entity\GroupContent;

/**
 * Form controller for creating a discussion in a group.
 *
 * @ingroup discussions
 */
class GroupDiscussionForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['comment'] = array(
      '#type' => 'textarea',
      '#title' => t('Text'),
      '#cols' => 60,
      '#resizable' => TRUE,
      '#rows' => 5,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $user = \Drupal::currentUser();

    // Set user ID property of the discussion.
    $entity->set('uid', $user->id());

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        // Add initial comment to discussion.
        $comment = Comment::create([
          'comment_type' => 'discussions_reply',
          'entity_id' => $entity->id(),
          'uid' => $user->id(),
          'name' => $user->getAccountName(),
          'status' => Comment::PUBLISHED,
          'entity_type' => 'discussion',
          'field_name' => 'comments',
          'comment_body' => $form_state->getValue('comment'),
        ]);

        $comment->save();

        // Add discussion to group.
        $group = $form_state->get('group');
        $plugin = $form_state->get('plugin');

        $group_content = GroupContent::create(array(
          'type' => $plugin->getContentTypeConfigId(),
          'gid' => $group->id(),
        ));

        $group_content->set('entity_id', $entity->id());

        $group_content->save();

        drupal_set_message($this->t('Created %label.', array(
          '%label' => $entity->label(),
        )));
        break;

      default:
        drupal_set_message($this->t('Saved %label.', array(
          '%label' => $entity->label(),
        )));
    }

    $form_state->setRedirect('entity.group.canonical', array(
      'group' => $group->id(),
    ));
  }

}
