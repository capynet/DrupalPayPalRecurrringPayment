<?php

namespace Drupal\paypal_sdk\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Plugin implementation of the 'paypal_agreement_id_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "paypal_agreement_id_field_formatter",
 *   label = @Translation("Paypal agreement ID"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class PaypalAgreementIdFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      /** @var Drupal\Core\TypedData\Plugin\DataType\StringData $agreement_id */
      $agreement_id = $item->get('agreement_id');
      $elements[$delta] = ['#markup' => $this->viewValue($agreement_id->getCastedValue())];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param $subscription_id
   * @return string The textual output generated.
   * The textual output generated.
   * @internal param \Drupal\Core\Field\FieldItemInterface $item One field
   *   item.*   One field item.
   *
   */
  protected function viewValue($subscription_id) {
    // TODO: We must implement how items are going to be displayed.
//    /** @var BillingAgreement $pba */
//    $pba = Drupal::service('paypal.billing.agreement');
//    $url = $pba->getUserAgreementLink($subscription_id);
//
//
//    if ($url) {
//      /** @var Drupal\Core\GeneratedLink $link */
//      $link = Link::fromTextAndUrl(
//        $this->getSetting('link_text'),
//        Url::fromUri($url, array(
//          'absolute' => TRUE,
//          'attributes' => array(
//            'target' => '_blank',
//            'class' => array('paypal-subscribe-link')
//          )
//        )))->toRenderable();
//
//      return render($link);
//    }

    return '';
  }
}
