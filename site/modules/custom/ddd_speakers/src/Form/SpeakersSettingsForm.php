<?php

namespace Drupal\ddd_speakers\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SpeakersSettingsForm.
 *
 * @package Drupal\ddd_speakers\Form
 *
 * @ingroup ddd_speakers
 */
class SpeakersSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'speakers_settings';
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
    $form['speakers_settings']['#markup'] = 'Settings form for Speakers. Manage field settings here.';
    return $form;
  }

}
