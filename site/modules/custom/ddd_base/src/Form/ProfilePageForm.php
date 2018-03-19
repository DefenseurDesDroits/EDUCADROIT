<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Form\ProfilePageForm.
 **/
namespace Drupal\ddd_base\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Language\LanguageInterface;
use Drupal\user\Entity\User;

class ProfilePageForm extends FormBase {

  public function getFormId() {
    return 'ddd_profile_page_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    if (!$account = \Drupal::currentUser()){
      return $this->redirect('ddd_forum.connection');
    }
    if (!$user = User::load($account->id()) ){
      return $this->redirect('ddd_forum.connection');
    }

    $form['#theme'] = 'ddd_profile_page_form';
    $form['#attributes']['autocomplete'] = ['off'];

    $form['surname'] = array(
      '#type' => 'textfield',
      '#title' => t('Nom'),
      '#required' => true,
      '#default_value'=> !empty($value = $user->get('field_surname')->getValue()) ? $value[0]['value'] : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );
    $form['firstname'] = array(
      '#type' => 'textfield',
      '#title' => t('Prénom'),
      '#required' => true,
      '#default_value'=> !empty($value = $user->get('field_firstname')->getValue()) ? $value[0]['value'] : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );
    $form['organization'] = array(
      '#type' => 'textfield',
      '#title' => t('Organisme'),
      '#required' => true,
      '#default_value'=> !empty($value = $user->get('field_organization')->getValue()) ? $value[0]['value'] : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );
    $form['capacities'] = array(
      '#type' => 'textfield',
      '#title' => t('Fonctions'),
      '#required' => true,
      '#default_value'=> !empty($value = $user->get('field_capacities')->getValue()) ? $value[0]['value'] : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );
    $form['password'] = [
      '#type' => 'password',
      '#title' => t('Mot de passe'),
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Envoyer'),
      '#attributes' => [
        'class' => ['btn']
      ]
    );
    return $form;
  }

  public function validateForm(array &$form,  FormStateInterface $form_state) {
    if (!$account = \Drupal::currentUser()){
      drupal_set_message(t('Une erreur s\'est produite. Vous avez été déconnecté.<br>Veuillez réessayer ultérieurement'), 'error');
      return $this->redirect('ddd_forum.connection');
    }
    if (!$user = User::load($account->id()) ){
      drupal_set_message(t('Une erreur s\'est produite. Vous avez été déconnecté.<br>Veuillez réessayer ultérieurement'), 'error');
      return $this->redirect('ddd_forum.connection');
    }
    if (empty($form_state->getValue('surname'))){
      $form_state->setErrorByName('surname', t('Veuillez renseigner votre Nom'));
    }
    if (empty($form_state->getValue('firstname'))){
      $form_state->setErrorByName('firstname', t('Veuillez renseigner votre Prénom'));
    }
    if (empty($form_state->getValue('organization'))){
      $form_state->setErrorByName('organization', t('Veuillez renseigner votre Organisme'));
    }
    if (empty($form_state->getValue('capacities'))){
      $form_state->setErrorByName('capacities', t('Veuillez renseigner vos Fonctions'));
    }
    if (!empty($password = $form_state->getValue('password')) ){
      if(strlen($password) < 6){
        $form_state->setErrorByName('password', t('Votre mot de passe est trop court. Veuillez insérer au minimum 6 caractères'));
      }
    }
  }

  public function submitForm(array &$form,  FormStateInterface $form_state) {
    if (!$account = \Drupal::currentUser()){
      drupal_set_message(t('Une erreur s\'est produite. Vous avez été déconnecté.<br>Veuillez réessayer ultérieurement'), 'error');
      return $this->redirect('ddd_forum.connection');
    }
    if (!$user = User::load($account->id()) ){
      drupal_set_message(t('Une erreur s\'est produite. Vous avez été déconnecté.<br>Veuillez réessayer ultérieurement'), 'error');
      return $this->redirect('ddd_forum.connection');
    }
    $values = $form_state->getValues();
    // data saving
    $user->set('field_surname', $values['surname']);
    $user->set('field_firstname', $values['firstname']);
    $user->set('field_organization', $values['organization']);
    $user->set('field_capacities', $values['capacities']);

    if (!empty($values['password'])){
      $user->setPassword($values['password']);
    }
    try {
      $user->save();
    } catch (Exception $e) {
      drupal_set_message(t('Une erreur s\'est produite. Vos modifications n\'ont pas pu être enregistrées.<br>Veuillez réessayer ultérieurement'), 'error');
      \Drupal::logger('ddd_base')->error($e->getMessage());
    } finally {
      drupal_set_message(t('Vos modifications ont été enregistrées'));
    }


  }
}
