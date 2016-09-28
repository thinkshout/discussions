<?php

namespace Drupal\discussions_email\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a discussions email settings form.
 */
class DiscussionsEmailSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'discussions_email_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('discussions_email.settings');

    $email_plugin_manager = \Drupal::service('plugin.manager.discussions_email');
    $email_plugin_definitions = $email_plugin_manager->getDefinitions();

    // TODO: Get installed email plugins.
    // TODO: Display notice if no email plugins installed.

    $email_plugin_options = array();

    $form['discussions_email_plugin_id'] = array(
      '#type' => 'select',
      '#title' => t('Email Plugin'),
      '#options' => $email_plugin_options,
      '#default_value' => $config->get('discussions_email_plugin_id'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::configFactory()->getEditable('discussions_email.settings')
      ->set('discussions_email_plugin_id', $form_state->getValue('discussions_email_plugin_id'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['discussions_email.settings'];
  }

}
