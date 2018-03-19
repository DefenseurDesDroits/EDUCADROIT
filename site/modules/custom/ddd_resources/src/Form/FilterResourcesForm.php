<?php
  /**
   * @file
   * Contains \Drupal\ddd_resources\Form\FilterResourcesForm.
   **/
  namespace Drupal\ddd_resources\Form;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  class FilterResourcesForm extends FormBase {

    public function getFormId() {
      return 'FilterResourcesForm';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $button_title = 'Rechercher') {
      $form['#theme'] = 'ddd_resources_filter_form';

      $q = \Drupal::request()->query->all();

      // Vocabulary Key Points
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "key_points");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $key_options = [];
      foreach ($terms as $key => $value) {
        $key_options[$key] = $value->toLink()->getText();
      }
      $form['key_points'] = array(
        '#type' => 'select',
        '#title' => t('Points Clés'),
        '#empty_option' => t('Points Clés'),
        '#title_display' => 'invisible',
        '#options' => $key_options,
        '#default_value' => (!empty($q['point_cle'])?$q['point_cle']:''),
        '#attributes' => ['class' => ['form-select', 'form-control']]
      );
      // Vocabulary Themes
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "themes");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $theme_options = [];
      foreach ($terms as $key => $value) {
        $theme_options[$key] = $value->toLink()->getText();
      }
      $form['themes'] = array(
        '#type' => 'select',
        '#title' => t('Thématique'),
        '#empty_option' => t('Thématique'),
        '#title_display' => 'invisible',
        '#options' => $theme_options,
        '#default_value' => (!empty($q['theme'])?$q['theme']:''),
        '#attributes' => ['class' => ['form-select', 'form-control']]
      );
      // Vocabulary Publics
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "public");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $public_options = [];
      foreach ($terms as $key => $value) {
        $public_options[$key] = $value->toLink()->getText();
      }
      $form['public'] = array(
        '#type' => 'select',
        '#title' => t('Public'),
        '#empty_option' => t('Public'),
        '#title_display' => 'invisible',
        '#options' => $public_options,
        '#default_value' => (!empty($q['public'])?$q['public']:''),
        '#attributes' => ['class' => ['form-select', 'form-control']]
      );
      // Vocabulary Formats
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "formats");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $format_options = [];
      foreach ($terms as $key => $value) {
        $format_options[$key] = $value->toLink()->getText();
      }
      $form['format'] = array(
        '#type' => 'select',
        '#title' => t('Format'),
        '#empty_option' => t('Format'),
        '#title_display' => 'invisible',
        '#options' => $format_options,
        '#default_value' => (!empty($q['format'])?$q['format']:''),
        '#attributes' => ['class' => ['form-select', 'form-control']]
      );
      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = array(
        '#type' => 'submit',
        '#value' => $button_title,
        '#button_type' => 'primary',
        '#attributes' => [
          'class' => ['btn']
        ]
      );
      return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
    }

    public function submitForm(array &$form,  FormStateInterface $form_state) {
      $values = $form_state->getValues();
      $query = [];
      if (!empty($values['key_points'])){
        $query['point_cle'] = $values['key_points'];
      }
      if (!empty($values['themes'])){
        $query['theme'] = $values['themes'];
      }
      if (!empty($values['public'])){
        $query['public'] = $values['public'];
      }
      if (!empty($values['format'])){
        $query['format'] = $values['format'];
      }
      $form_state->setRedirect('ddd_resources.resources', $query);
    }
  }
