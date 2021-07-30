<?php
/**
 * @file
 * Contains \Drupal\ddd_resources\Form\FilterResourcesForm.
 **/

namespace Drupal\ddd_resources\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FilterResourcesForm extends FormBase
{

  public function getFormId()
  {
    return 'FilterResourcesForm';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $button_title = 'Rechercher')
  {
    $form['#theme'] = 'ddd_resources_filter_form';

    $q = \Drupal::request()->query->all();

    $form['search_api_fulltext'] = array(
      '#type' => 'textfield',
      '#title' => t('Recherche texte dans les ressources'),
      '#default_value' => (!empty($q['search_api_fulltext']) ? $q['search_api_fulltext'] : ''),
      '#attributes' => ['class' => ['form-control']],
      '#placeholder' => t('Recherche libre')
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

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    $query = [];
    if (!empty($values['search_api_fulltext'])) {
      $query['search_api_fulltext'] = $values['search_api_fulltext'];
    }
    $form_state->setRedirect('ddd_resources.resources', $query);
  }
}
