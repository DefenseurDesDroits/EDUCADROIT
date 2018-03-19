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
class ReferrerForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\ddd_speakers\Entity\Referrer */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;
    // $form['#attached']['library'][] = 'drupal.ajax';
    $form['langcode'] = array(
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.referrer.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}
