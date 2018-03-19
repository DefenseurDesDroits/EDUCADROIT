<?php

namespace Drupal\ddd_courses\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CourseSettingsForm.
 *
 * @package Drupal\ddd_courses\Form
 *
 * @ingroup ddd_courses
 */
class CourseSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'course_settings';
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
    $form['course_settings']['#markup'] = 'Settings form for Course. Manage field settings here.';
    return $form;
  }

}
