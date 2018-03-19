<?php
  namespace Drupal\ddd_config;
  use Drupal\Component\Utility\Unicode;
  use Drupal\Core\Entity\EntityForm;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Language\LanguageInterface;
  class ConfigForm extends EntityForm {
    /**
     * {@inheritdoc}
     */
    public function form(array $form, FormStateInterface $form_state) {
      $form = parent::form($form, $form_state);
      /** @var \Drupal\ddd_config\Entity\ConfigInterface $entity */
      $entity = $this->entity;
      $header = $entity->getHeader();
      $form['header_ddd_logo'] = [
        '#type' => 'managed_file',
        '#name' => 'header_ddd_logo',
        '#title' => t('Logo - Header'),
        '#required' => FALSE,
        '#upload_location' => 'public://manage/logo/',
        '#default_value' => $header['ddd_logo'],
      ];
      $form['ddd_cookie_disclaimer'] = [
        '#type' => 'textarea',
        '#title' => t('Bandeau cookie'),
        '#required' => FALSE,
        '#default_value' => $header['ddd_cookie_disclaimer'],
      ];
      $footer = $entity->getFooter();
      $form['footer_follow_title'] = [
        '#type' => 'textarea',
        '#title' => t('Titre "Nous suivre" - Footer'),
        '#required' => FALSE,
        '#default_value' => $footer['follow_title'],
      ];
      $form['footer_facebook_link'] = [
        '#title' => t('Lien Facebook - Footer'),
        '#type' => 'url',
        '#required' => FALSE,
        '#default_value' => $footer['facebook_link'],
      ];
      $form['footer_twitter_link'] = [
        '#type' => 'url',
        '#title' => t('Lien Twitter - Footer'),
        '#required' => FALSE,
        '#default_value' => $footer['twitter_link'],
      ];
      $form['footer_ddd_link'] = [
        '#type' => 'url',
        '#title' => t('Lien Le Défenseur des droits - Footer'),
        '#required' => FALSE,
        '#default_value' => $footer['ddd_link'],
      ];
      $form['footer_ddd_logo'] = [
        '#type' => 'managed_file',
        '#name' => 'footer_ddd_logo',
        '#title' => t('Logo - Footer'),
        '#required' => FALSE,
        '#upload_location' => 'public://manage/logo/',
        '#default_value' => $footer['ddd_logo'],
      ];
      $form['footer_ddd_description'] = [
        '#type' => 'textarea',
        '#title' => t('Description - Footer'),
        '#required' => FALSE,
        '#default_value' => $footer['ddd_description'],
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
