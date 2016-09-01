<?php

namespace Drupal\discussions\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for Discussions.
 *
 * @ingroup discussions
 */
class DiscussionController extends ControllerBase {

  protected $storage;

  protected $typeStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityStorageInterface $storage, EntityStorageInterface $type_storage) {
    $this->storage = $storage;
    $this->typeStorage = $type_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = $container->get('entity_type.manager');

    return new static(
      $entity_type_manager->getStorage('discussion'),
      $entity_type_manager->getStorage('discussion_type')
    );
  }

  /**
   * Presents the creation form for Discussions of given bundle/type.
   *
   * @param EntityInterface $discussion_type
   *   The custom bundle to add.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request object.
   *
   * @return array
   *   A form array as expected by drupal_render().
   */
  public function addForm(EntityInterface $discussion_type, Request $request) {
    $entity = $this->storage->create(array(
      'type' => $discussion_type->id(),
    ));

    return $this->entityFormBuilder()->getForm($entity);
  }

}
