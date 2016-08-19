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
   * Displays add links for available bundles/types for Discussions.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request object.
   *
   * @return array
   *   A render array for a list of the Discussion bundles/types that can be
   *   added or if there is only one bundle/type defined for the site, the
   *   function returns the add page for that bundle/type.
   */
  public function add(Request $request) {
    $types = $this->typeStorage->loadMultiple();

    if ($types && count($types) == 1) {
      $type = reset($types);
      return $this->addForm($type, $request);
    }

    if (count($types) === 0) {
      return array(
        '#markup' => $this->t('You have not created any Discussion types yet. @link to add a new type.', array(
          '@link' => $this->l($this->t('Go to the Discussion Type creation page'), Url::fromRoute('discussion_type.add_form')),
        )),
      );
    }

    return array('#theme' => 'discussions_discussion_add_list', '#content' => $types);
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
