<?php

namespace Drupal\ddd_speakers\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ReferrerSettingsForm.
 *
 * @package Drupal\ddd_speakers\Form
 *
 * @ingroup ddd_speakers
 */
class ReferrerSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'referrer_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['referrer_settings']['#markup'] = 'Settings form for Referrer. Manage field settings here.';
    return $form;
  }

}
