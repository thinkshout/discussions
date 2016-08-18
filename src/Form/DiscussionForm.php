<?php

namespace Drupal\discussions\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Form controller for Discussion entity edit form.
 *
 * @ingroup discussions
 */
class DiscussionForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $group = \Drupal::routeMatch()->getParameter('group');
    $entity->set('group', $group);

    $user = User::load(\Drupal::currentUser()->id());
    $entity->set('user', $user);

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created %label.', array(
          '%label' => $entity->label(),
        )));
        break;

      default:
        drupal_set_message($this->t('Saved %label.', array(
          '%label' => $entity->label(),
        )));
    }

    $form_state->setRedirect('entity.discussion.canonical', array(
      'discussion_group' => $group->id(),
      'discussion' => $entity->id(),
    ));
  }

}
