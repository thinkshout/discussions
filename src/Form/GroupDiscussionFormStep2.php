<?php

namespace Drupal\discussions\Form;

use Drupal\group\Entity\Form\GroupContentForm;
use Drupal\user\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form creating a Discussion in a group.
 */
class GroupDiscussionFormStep2 extends GroupContentForm {

  /**
   * The private store for temporary group Discussions.
   *
   * @var \Drupal\user\PrivateTempStore
   */
  protected $privateTempStore;

  /**
   * Creates a new GroupDiscussionFormStep2.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   *   The factory for the temp store object.
   */
  public function __construct(EntityManagerInterface $entity_manager, PrivateTempStoreFactory $temp_store_factory) {
    parent::__construct($entity_manager);
    $this->privateTempStore = $temp_store_factory->get('discussion_add_temp');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('user.private_tempstore')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['entity_id']['#access'] = FALSE;
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);

    $actions['submit']['#value'] = $this->t('Create Discussion in group');
    $actions['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#submit' => ['::submitForm', '::back'],
      '#limit_validation_errors' => [],
    ];

    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $storage_id = $form_state->get('storage_id');

    // We can now safely save the Discussion and set its ID on the group content.
    $discussion = $this->privateTempStore->get("$storage_id:discussion");
    $discussion->save();

    $this->entity->set('entity_id', $discussion->id());

    // We also clear the private store so we can start fresh next time around.
    $this->privateTempStore->delete("$storage_id:step");
    $this->privateTempStore->delete("$storage_id:discussion");

    return parent::save($form, $form_state);
  }

  /**
   * Goes back to step 1 of group Discussion creation.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @see \Drupal\discussion\Controller\GroupDiscussionController::addForm()
   * @see \Drupal\discussion\Form\GroupDiscussionFormStep1
   */
  public function back(array &$form, FormStateInterface $form_state) {
    $storage_id = $form_state->get('storage_id');
    $this->privateTempStore->set("$storage_id:step", 1);

    // Disable any URL-based redirect when going back to the previous step.
    $request = $this->getRequest();
    $form_state->setRedirectUrl(Url::fromRoute('<current>', [], ['query' => $request->query->all()]));
    $request->query->remove('destination');
  }

}
