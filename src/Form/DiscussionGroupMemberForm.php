<?php

namespace Drupal\discussions\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Discussion Group Member entity edit form.
 *
 * @ingroup discussions
 */
class DiscussionGroupMemberForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created %label.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved %label.', [
          '%label' => $entity->label(),
        ]));
    }

    $form_state->setRedirect('entity.discussion_group_member.canonical', ['discussion_group_member' => $entity->id()]);
  }

}
