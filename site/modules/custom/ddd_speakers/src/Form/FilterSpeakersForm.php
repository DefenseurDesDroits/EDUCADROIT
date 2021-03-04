<?php
  /**
   * @file
   * Contains \Drupal\ddd_speakers\Form\FilterSpeakersForm.
   **/
  namespace Drupal\ddd_speakers\Form;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  class FilterSpeakersForm extends FormBase {

    public function getFormId() {
      return 'ddd_speakers_filter_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['#theme'] = 'ddd_speakers_filter_form';

      $q = \Drupal::request()->query->all();

      $form['search'] = array(
        '#type' => 'textfield',
        '#title' => t('Recherche'),
        '#title_display' => 'invisible',
        '#default_value' => (!empty($q['search'])?$q['search']:''),
        '#attributes' => ['class' => ['form-control']],
        '#placeholder' => t('Recherche libre')
      );

      // Vocabulary Regions
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "regions");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $regions_options = [];
      foreach ($terms as $key => $value) {
        $regions_options[$key] = $value->toLink()->getText();
      }
      $form['regions'] = array(
        '#type' => 'select',
        '#title' => t('Régions'),
        '#empty_option' => t('Régions'),
        '#title_display' => 'invisible',
        '#options' => $regions_options,
        '#default_value' => (!empty($q['region'])?$q['region']:''),
        '#attributes' => ['class' => ['form-select', 'form-control']]
      );
      // Vocabulary Contributors' Type
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "speaker_type");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $type_options = [];
      foreach ($terms as $key => $value) {
        $type_options[$key] = $value->toLink()->getText();
      }
      $form['type'] = array(
        '#type' => 'select',
        '#title' => t('Type d\'intervenant'),
        '#empty_option' => t('Type d\'intervenant'),
        '#title_display' => 'invisible',
        '#options' => $type_options,
        '#default_value' => (!empty($q['type'])?$q['type']:''),
        '#attributes' => ['class' => ['form-select', 'form-control']]
      );
      // Vocabulary Speciality
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "speciality");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $spe_options = [];
      foreach ($terms as $key => $value) {
        $spe_options[$key] = $value->toLink()->getText();
      }
      $form['speciality'] = array(
        '#type' => 'select',
        '#title' => t('Spécialité'),
        '#empty_option' => t('Spécialité'),
        '#title_display' => 'invisible',
        '#options' => $spe_options,
        '#default_value' => (!empty($q['specialite'])?$q['specialite']:''),
        '#attributes' => ['class' => ['form-select', 'form-control']]
      );
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Rechercher'),
        '#attributes' => [
          'class' => ['btn']
        ]
      );
      return $form;
    }

    public function validateForm(array &$form,  FormStateInterface $form_state) {
    }

    public function submitForm(array &$form,  FormStateInterface $form_state) {
      $values = $form_state->getValues();
      $query = [];
      if (!empty($values['search'])){
        $query['search'] = $values['search'];
      }
      if (!empty($values['regions'])){
        $query['region'] = $values['regions'];
      }
      if (!empty($values['type'])){
        $query['type'] = $values['type'];
      }
      if (!empty($values['speciality'])){
        $query['specialite'] = $values['speciality'];
      }
      $form_state->setRedirect('ddd_speakers.speakers', $query);
    }
  }
