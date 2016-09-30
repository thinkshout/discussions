<?php

namespace Drupal\discussions;

use Drupal\discussions\Entity\Discussion;
use Drupal\group\Entity\GroupContentType;

/**
 * The group discussion service.
 */
class GroupDiscussionService implements GroupDiscussionServiceInterface {

  /**
   * {@inheritdoc}
   */
  public function getGroupDiscussion($group_id, $discussion_id) {
    /** @var Discussion $discussion */
    $discussion = Discussion::load($discussion_id);

    $type = $discussion->bundle();

    $group_content_types = GroupContentType::loadByContentPluginId("group_discussion:$type");
    if (!empty($group_content_types)) {
      // Load all the group content for this discussion.
      $group_contents = \Drupal::entityTypeManager()
        ->getStorage('group_content')
        ->loadByProperties([
          'type' => array_keys($group_content_types),
          'entity_id' => $discussion->id(),
        ]);

      if (!empty($group_contents)) {
        /** @var \Drupal\group\Entity\GroupContentInterface $group_content */
        foreach ($group_contents as $group_content) {
          if ($group_content->getGroup()->id() == $group_id) {
            return $discussion;
          }
        }
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function addComment() {

  }

}
