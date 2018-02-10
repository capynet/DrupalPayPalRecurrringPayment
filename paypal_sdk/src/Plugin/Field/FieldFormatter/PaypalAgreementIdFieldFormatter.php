<?php

namespace Drupal\paypal_sdk\Plugin\Field\FieldFormatter;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\paypal_sdk\Services\BillingAgreement;
use Drupal\paypal_sdk\Controller\PaypalSDKController;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * Plugin implementation of the 'paypal_agreement_id_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "paypal_agreement_id_field_formatter",
 *   label = @Translation("Paypal agreement ID"),
 *   field_types = {
 *     "paypal_agreement_id_field_type"
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
      $agreement_id = $item->getString();
      $elements[$delta] = ['#markup' => $this->viewValue($agreement_id)];
    }

    return $elements;
  }


  /**
   * @param $agreementId
   *
   * @return mixed
   */
  protected function viewValue($agreementId) {
    $agreement = PaypalSDKController::getAgreement($agreementId);
    $data = [];

    /** @var \PayPal\Api\AgreementDetails $details */
    $details = $agreement->getAgreementDetails();
    $data['next_billing_date'] = $details->getNextBillingDate();
    $data['last_payment_amount'] = $details->getLastPaymentAmount();
    $data['cycles_completed'] = $details->getCyclesCompleted();
    $data['cycles_remaining'] = $details->getCyclesRemaining();
    $data['startDate'] = $agreement->getStartDate();
    $data['state'] = $agreement->getState();

    $utcTimezone = new \DateTimeZone('UTC');
    $next_billing_date = new \DateTime($data['next_billing_date'], $utcTimezone);
    $start_date = new \DateTime($data['startDate'], $utcTimezone);

    $build['table'] = [
      '#theme' => 'table',
      '#header' => [
        $this->t('Started at'),
        $this->t('Next billing date'),
        $this->t('State'),
        $this->t('Cycles completed'),
        $this->t('Cycles remaining'),
        $this->t('Actions'),
      ],
      '#rows' => [
        [
          ['data' => $start_date->format('m/d/Y H:i')],
          ['data' => $next_billing_date->format('m/d/Y H:i')],
          ['data' => $data['state']],
          ['data' => $data['cycles_completed']],
          ['data' => $data['cycles_remaining']],
        ]
      ],
      '#empty' => $this->t("There are no PayPal subscriptions for this user."),
    ];


    $actions = [];
    switch ($agreement->getState()) {
      case BillingAgreement::AGREEMENT_ACTIVE:
        // Suspend link.
        $actions['Suspend'] = Url::fromRoute('paypal_sdk.agreement_update_status_form', ['agreement_id' => $agreementId, 'status' => BillingAgreement::AGREEMENT_SUSPENDED]);
        // Cancel link.
        $actions['Cancel'] = Url::fromRoute('paypal_sdk.agreement_update_status_form', ['agreement_id' => $agreementId, 'status' => BillingAgreement::AGREEMENT_CANCELED]);
        break;
      case BillingAgreement::AGREEMENT_SUSPENDED:
        // Re-activate it
        $actions['Reactivate'] = Url::fromRoute('paypal_sdk.agreement_update_status_form', ['agreement_id' => $agreementId, 'status' => BillingAgreement::AGREEMENT_REACTIVE]);
        // Cancel link.
        $actions['Cancel'] = Url::fromRoute('paypal_sdk.agreement_update_status_form', ['agreement_id' => $agreementId, 'status' => BillingAgreement::AGREEMENT_CANCELED]);
        break;
      case BillingAgreement::AGREEMENT_CANCELED:
        // TODO: Do we need to allow active the agreement or just need to create a new one?
        break;

    }

    $links = [];

    foreach ($actions as $label => $url) {
      $links[] = Link::fromTextAndUrl($label, $url)->toString()->getGeneratedLink();
    }

    $renderable_links = ['#markup' => implode(' | ', $links)];
    $build['table']['#rows'][0][] = ['data' => render($renderable_links)];

    // Convert $build to HTML and attach any asset libraries.
    $html = \Drupal::service('renderer')->renderRoot($build);

    return $html;
  }
}
