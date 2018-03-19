<?php

namespace Drupal\ddd_resources\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ResourcesSettingsForm.
 *
 * @package Drupal\ddd_resources\Form
 *
 * @ingroup ddd_resources
 */
class ResourcesSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'resources_settings';
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
    $form['resources_settings']['#markup'] = 'Settings form for Resources. Manage field settings here.';
    return $form;
  }

}
