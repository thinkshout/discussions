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

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $user = $entity->user->entity;

    $row['mail'] = $user->mail->value;

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
