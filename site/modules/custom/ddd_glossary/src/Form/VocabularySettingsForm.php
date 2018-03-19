<?php

namespace Drupal\ddd_glossary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class GlossarySettingsForm.
 *
 * @package Drupal\ddd_glossary\Form
 *
 * @ingroup ddd_glossary
 */
class VocabularySettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'vocabulary_settings';
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
    $form['vocabulary_settings']['#markup'] = 'Settings form for Vocabulary. Manage field settings here.';
    return $form;
  }

}
