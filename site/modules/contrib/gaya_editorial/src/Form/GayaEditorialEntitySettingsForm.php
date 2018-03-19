<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\Form\GayaEditorialEntitySettingsForm.
 */

namespace Drupal\gaya_editorial\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class GayaEditorialEntitySettingsForm.
 *
 * @package Drupal\gaya_editorial\Form
 *
 * @ingroup gaya_editorial
 */
class GayaEditorialEntitySettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'GayaEditorialEntity_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }


  /**
   * Defines the settings form for Gaya editorial entity entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['GayaEditorialEntity_settings']['#markup'] = 'Settings form for Gaya editorial entity entities. Manage field settings here.';
    return $form;
  }

}
