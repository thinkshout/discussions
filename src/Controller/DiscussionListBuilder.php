<?php

namespace Drupal\discussions\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list of Discussions.
 */
class DiscussionListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['subject'] = $this->t('Subject');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['subject'] = $entity->title;

    return $row + parent::buildRow($entity);
  }

}
