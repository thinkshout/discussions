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

    /** @var \Drupal\discussions_email\DiscussionsEmailPluginManager $email_plugin_manager */
    $email_plugin_manager = \Drupal::service('plugin.manager.discussions_email');
    $email_plugin_definitions = $email_plugin_manager->getDefinitions();

    // TODO: Display notice if no email plugins installed.

    $email_plugin_options = array(
      '' => t('None'),
    );

    if (!empty($email_plugin_definitions)) {
      foreach ($email_plugin_definitions as $id => $definition) {
        $email_plugin_options[$id] = $definition['label'];
      }

      $form['plugin_id'] = [
        '#type' => 'select',
        '#title' => $this->t('Email Plugin'),
        '#options' => $email_plugin_options,
        '#default_value' => $config->get('plugin_id'),
      ];

      $form['filter_css_classes'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Filter email CSS classes'),
        '#description' => $this->t('List the CSS classes denoting divs that you want to filter out of incoming message. Used for removing excessive message quoting generated by email clients. Separate with commas.'),
        '#default_value' => $config->get('filter_css_classes'),
      ];

      $form['email_author'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Send email to author'),
        '#description' => $this->t('If checked, email will be sent to the author of the post. Leave unchecked to exclude author from email.'),
        '#default_value' => $config->get('email_author'),
      ];

      $form['process_bounces'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Process bounces'),
        '#description' => $this->t('Set a user\'s group status to inactive when email cannot be delivered to the user\'s address.'),
        '#default_value' => $config->get('process_bounces'),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::configFactory()->getEditable('discussions_email.settings')
      ->set('plugin_id', $form_state->getValue('plugin_id'))
      ->set('filter_css_classes', $form_state->getValue('filter_css_classes'))
      ->set('email_author', $form_state->getValue('email_author'))
      ->set('process_bounces', $form_state->getValue('process_bounces'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['discussions_email.settings'];
  }

}
