<?php

namespace Drupal\discussions\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list of Discussion Group Members.
 *
 * @ingroup discussions
 */
class DiscussionGroupMemberListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['mail'] = $this->t('Mail');
    $header['pending'] = $this->t('Pending Approval');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $user = $entity->user->entity;

    $row['mail'] = $user->mail->value;
    $row['pending'] = ($entity->pending->value == 1) ? $this->t('Yes') : $this->t('No');

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function buildOperations(EntityInterface $entity) {
    // Temporarily disable operation links.
    // Need to get Discussion Group ID and Discussion Group Member ID.
    return array();
  }

}
