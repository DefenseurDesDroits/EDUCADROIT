<?php

namespace Drupal\ddd_medias\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MediasSettingsForm.
 *
 * @package Drupal\ddd_medias\Form
 *
 * @ingroup ddd_medias
 */
class MediasSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'medias_settings';
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
    $form['medias_settings']['#markup'] = 'Settings form for Medias. Manage field settings here.';
    return $form;
  }

}
