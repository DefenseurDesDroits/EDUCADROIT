<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\Form\GayaEditorialEntityForm.
 */

namespace Drupal\gaya_editorial\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Gaya editorial entity edit forms.
 *
 * @ingroup gaya_editorial
 */
class GayaEditorialEntityForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\gaya_editorial\Entity\GayaEditorialEntity */
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Gaya editorial entity.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Gaya editorial entity.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.gaya_editorial_entity.edit_form', ['gaya_editorial_entity' => $entity->id()]);
  }

}
