<?php

namespace Drupal\ddd_speakers\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the ddd_speakers entity edit forms.
 *
 * @ingroup ddd_speakers
 */
class SpeakersForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\ddd_speakers\Entity\Speakers */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.speakers.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}
