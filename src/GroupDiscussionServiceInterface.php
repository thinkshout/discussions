<?php

namespace Drupal\discussions;

/**
 * Interface for the group discussion service.
 */
interface GroupDiscussionServiceInterface {

  /**
   * Gets a discussion only if part of the given group.
   *
   * @param int $group_id
   *   The ID of the group the discussion is part of.
   * @param int $discussion_id
   *   The ID of the discussion to load.
   *
   * @return \Drupal\discussions\Entity\Discussion
   *   The discussion.
   */
  public function getGroupDiscussion($group_id, $discussion_id);

  /**
   * Adds a new comment to a discussion.
   *
   * @return bool
   *   TRUE if comment added, FALSE otherwise.
   */
  public function addComment();

}
