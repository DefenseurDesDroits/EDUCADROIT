<?php
  namespace Drupal\ddd_contact;
  use Drupal\Component\Utility\Unicode;
  use Drupal\Core\Entity\EntityForm;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Language\LanguageInterface;
  class ContactConfigForm extends EntityForm {
    /**
     * {@inheritdoc}
     */
    public function form(array $form, FormStateInterface $form_state) {
      $form = parent::form($form, $form_state);
      /** @var \Drupal\ddd_contact\Entity\ContactConfigInterface $entity */
      $entity = $this->entity;
      $form['label'] = [
        '#type' => 'textfield',
        '#title' => t('Label'),
        '#required' => TRUE,
        '#default_value' => $entity->label(),
      ];
      $form['email'] = [
        '#type' => 'textfield',
        '#title' => t('Email administrateur'),
        '#description' => 'Cet email est utilisé lors de l\'envoie des demandes de contact',
        '#default_value' => $entity->getEmail(),
      ];
      $form['teaser'] = [
        '#type' => 'textarea',
        '#title' => t('Chapô'),
        '#required' => FALSE,
        '#default_value' => $entity->getTeaser(),
      ];
      return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state) {
      $entity = $this->entity;
      $is_new = !$entity->getOriginalId();
      if ($is_new) {
        $machine_name = \Drupal::transliteration()->transliterate($entity->label(),
          LanguageInterface::LANGCODE_DEFAULT, '_');
        $entity->set('id', Unicode::strtolower($machine_name));
        drupal_set_message(t('The %label page has been created.', array('%label' => $entity->label())));
      }
      else {
        drupal_set_message(t('Updated the %label page.',
          array('%label' => $entity->label())));
      }
      $entity->save();
    }
  }
