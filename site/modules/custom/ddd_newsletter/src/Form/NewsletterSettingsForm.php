<?php

namespace Drupal\ddd_newsletter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NewsletterSettingsForm.
 *
 * @package Drupal\ddd_newsletter\Form
 *
 * @ingroup ddd_newsletter
 */
class NewsletterSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'newsletter_settings';
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
    $form['newsletter_settings']['#markup'] = 'Settings form for Newsletter. Manage field settings here.';
    return $form;
  }

}
