<?php

namespace Drupal\discussions\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a list of Discussion Groups.
 *
 * @ingroup discussions
 */
class DiscussionGroupListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['title'] = $this->t('Title');
    $header['private'] = $this->t('Private');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['title'] = $entity->label();
    $row['private'] = ($entity->private) ? t('Yes') : t('No');

    return $row + parent::buildRow($entity);
  }

}
