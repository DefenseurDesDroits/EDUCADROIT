<?php

namespace Drupal\ddd_base\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class HomeSettingsForm.
 *
 * @package Drupal\ddd_base\Form
 *
 * @ingroup ddd_base
 */
class HomeSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'home_settings';
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
    $form['home_settings']['#markup'] = 'Settings form for Home. Manage field settings here.';
    return $form;
  }

}
