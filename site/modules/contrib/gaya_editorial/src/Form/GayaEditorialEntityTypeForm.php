<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\Form\GayaEditorialEntityTypeForm.
 */

namespace Drupal\gaya_editorial\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class GayaEditorialEntityTypeForm.
 *
 * @package Drupal\gaya_editorial\Form
 */
class GayaEditorialEntityTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $gaya_editorial_entity_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $gaya_editorial_entity_type->label(),
      '#description' => $this->t("Label for the Gaya editorial entity type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $gaya_editorial_entity_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\gaya_editorial\Entity\GayaEditorialEntityType::load',
      ),
      '#disabled' => !$gaya_editorial_entity_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $gaya_editorial_entity_type = $this->entity;
    $status = $gaya_editorial_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Gaya editorial entity type.', [
          '%label' => $gaya_editorial_entity_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Gaya editorial entity type.', [
          '%label' => $gaya_editorial_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($gaya_editorial_entity_type->urlInfo('collection'));
  }

}
