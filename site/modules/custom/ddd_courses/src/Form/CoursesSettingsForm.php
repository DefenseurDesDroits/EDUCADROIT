<?php

namespace Drupal\ddd_courses\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CoursesSettingsForm.
 *
 * @package Drupal\ddd_courses\Form
 *
 * @ingroup ddd_courses
 */
class CoursesSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'courses_settings';
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
    $form['courses_settings']['#markup'] = 'Settings form for Courses. Manage field settings here.';
    return $form;
  }

}
