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
class GlossarySettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'glossary_settings';
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
    $form['glossary_settings']['#markup'] = 'Settings form for Glossary. Manage field settings here.';
    return $form;
  }

}
