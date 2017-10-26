<?php

namespace Drupal\paypal_sdk\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Implements an example form.
 */
class PaypalAdminForm extends ConfigFormBase {

  public function __construct(ConfigFactoryInterface $configFactory) {
    parent::__construct($configFactory);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'paypal_admin_form';
  }

  protected function getEditableConfigNames() {
    return ['config.paypal_credentials'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $plan_id = NULL) {
    $paypal_config = $this->config('config.paypal_credentials');
    $form['credentials']['client_id'] = array(
      '#type' => 'textfield',
      '#title' => t('Client ID'),
      '#description' => 'Paypal application Client ID',
      '#maxlength' => 255,
      '#required' => TRUE,
      '#default_value' => $paypal_config->get('client_id')? $paypal_config->get('client_id') : '',
    );

    $form['credentials']['client_secret'] = array(
      '#type' => 'textfield',
      '#title' => t('Client Secret'),
      '#description' => 'Paypal application Client Secret',
      '#maxlength' => 255,
      '#required' => TRUE,
      '#default_value' => $paypal_config->get('client_secret')? $paypal_config->get('client_secret') : '',
    );

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('config.paypal_credentials')
      ->set('client_id', $form_state->getValue(array('client_id')))
      ->set('client_secret', $form_state->getValue(array('client_secret')))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
