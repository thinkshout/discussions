<?php

namespace Drupal\discussions;

use Drupal\comment\Entity\Comment;
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
  public function getDiscussionGroup($discussion_id) {
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

      // Return the first group containing the discussion.
      // A discussion should only exist in one group.
      if (!empty($group_contents)) {
        /** @var \Drupal\group\Entity\GroupContentInterface $group_content */
        $group_content = current(array_values($group_contents));

        return $group_content->getGroup();
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function addComment($discussion_id, $parent_comment_id, $user_id, $comment_body, array $files = []) {
    $user = \Drupal::currentUser();

    /** @var Discussion $discussion */
    $discussion = Discussion::load($discussion_id);

    if (empty($discussion)) {
      return FALSE;
    }

    $comment = Comment::create([
      'comment_type' => 'discussions_reply',
      'pid' => (!empty($parent_comment_id)) ? $parent_comment_id : NULL,
      'entity_id' => $discussion->id(),
      'subject' => $discussion->subject,
      'uid' => $user_id,
      'name' => $user->getAccountName(),
      'status' => Comment::PUBLISHED,
      'entity_type' => 'discussion',
      'field_name' => 'discussions_comments',
      'comment_body' => [
        'value' => $comment_body,
        'format' => 'discussions_email_html',
      ],
    ]);

    $attachments = [];
    foreach ($files as $file) {
      $attachments[] = ['target_id' => $file->id()];
    }

    if (!empty($attachments)) {
      $comment->discussions_attachments->setValue($attachments);
    }

    return $comment->save();
  }

}
