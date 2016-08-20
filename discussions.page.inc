<?php

/**
 * @file
 * Page callback for Discussion entities.
 */

use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for a custom entity type creation list templates.
 *
 * Default template: discussions-discussion-add-list.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - content: An array of Discussion Types.
 *
 * @see block_content_add_page()
 */
function template_preprocess_discussions_discussion_add_list(&$variables) {
  $variables['types'] = array();
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = array(
      'link' => Link::fromTextAndUrl($type->label(), Url::fromRoute('entity.discussion.add_form', array(
        'discussion_type' => $type->id(),
      ), array('query' => $query))),
      'description' => array(
        '#markup' => $type->label(),
      ),
      'title' => $type->label(),
      'localized_options' => array(
        'query' => $query,
      ),
    );
  }
}
