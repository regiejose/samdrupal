<?php

namespace Drupal\jwt_parser\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class JwtParserConfigForm extends ConfigFormBase {
  /**
   * Config name.
   */
  protected function getEditableConfigNames() {
    return ['jwt_parser.settings'];
  }

  /**
   * Form ID.
   */
  public function getFormId() {
    return 'jwt_parser_config_form';
  }

  /**
   * Build form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('jwt_parser.settings');

    $form['secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('JWT secret'),
      '#default_value' => $config->get('secret'),
      '#required' => TRUE,
    ];

    $form['algorithm'] = [
      '#type' => 'textfield',
      '#title' => $this->t('JWT algorithm'),
      '#default_value' => $config->get('algorithm'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit handler.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('jwt_parser.settings')
      ->set('secret', $form_state->getValue('secret'))
      ->set('algorithm', $form_state->getValue('algorithm'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}