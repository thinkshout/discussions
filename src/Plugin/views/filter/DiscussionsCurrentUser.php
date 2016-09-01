<?php

namespace Drupal\discussions\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\BooleanOperator;

/**
 * Filters discussions by current user.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("discussions_current_user")
 */
class DiscussionsCurrentUser extends BooleanOperator {

  /**
   * Override the query so that no filtering takes place if the user doesn't
   * select any options.
   */
  public function query() {
    $this->ensureMyTable();
    $field = "$this->tableAlias.uid";

    $uid = \Drupal::currentUser()->id();

    $this->query->addWhere($this->options['group'], $field, $uid, '=');
  }

}
