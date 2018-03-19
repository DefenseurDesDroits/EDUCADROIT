<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Form\FirstConnectionForm.
 **/
namespace Drupal\ddd_base\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Language\LanguageInterface;
use Drupal\user\Entity\User;
use Drupal\Component\Utility\Random;
use Drupal\Core\Url;

class FirstConnectionForm extends FormBase {

  public function getFormId() {
    return 'ddd_first_connection_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    if (!empty($token = \Drupal::request()->query->get('token'))){
      $query = \Drupal::entityQuery('user');
      $query->condition('field_first_connection_hash', $token);
      $result = $query->execute();
      if (empty($result)){
        return $this->redirect('ddd_forum.connection');
      }
      $account =  User::load(reset($result));
    }

    $form['#theme'] = 'ddd_first_connection_form';

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#required' => true,
      '#default_value' => !empty($account) ? $account->mail->value : '',
      '#attributes' => [
        'class' => ['form-control'],
      ]
    );

    $form['surname'] = array(
      '#type' => 'textfield',
      '#title' => t('Nom'),
      '#required' => true,
      '#default_value' => !empty($account) ? $account->field_surname->value : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );
    $form['firstname'] = array(
      '#type' => 'textfield',
      '#title' => t('Prénom'),
      '#required' => true,
      '#default_value' => !empty($account) ? $account->field_firstname->value : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );
    $form['organization'] = array(
      '#type' => 'textfield',
      '#title' => t('Organisme'),
      '#required' => true,
      '#default_value' => !empty($account) ? $account->field_organization->value : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );
    $form['capacities'] = array(
      '#type' => 'textfield',
      '#title' => t('Fonction'),
      '#required' => true,
      '#default_value' => !empty($account) ? $account->field_capacities->value : '',
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => ['off']
      ]
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Activer mon compte'),
      '#attributes' => [
        'class' => ['btn']
      ]
    );
    return $form;
  }

  public function validateForm(array &$form,  FormStateInterface $form_state) {
    if (empty($email = $form_state->getValue('email'))){
      $form_state->setErrorByName('email', t('Veuillez renseigner votre courriel'));
    }
    if (empty($surname = $form_state->getValue('surname'))){
      $form_state->setErrorByName('surname', t('Veuillez renseigner votre nom'));
    }
    if (empty($firstname = $form_state->getValue('firstname'))){
      $form_state->setErrorByName('firstname', t('Veuillez renseigner votre prénom'));
    }
    if (empty($organization = $form_state->getValue('organization'))){
      $form_state->setErrorByName('organization', t('Veuillez renseigner votre organisme'));
    }
    if (empty($capacities = $form_state->getValue('capacities'))){
      $form_state->setErrorByName('capacities', t('Veuillez renseigner vos fonctions'));
    }
    if (!\Drupal::service('email.validator')->isValid($email)){
      $form_state->setErrorByName('email', t('Veuillez renseigner un courriel valide'));
    }
    $query = \Drupal::entityQuery('user');
    $query->condition('mail', $email);
    $ids = $query->execute();

    if (empty($ids)){
      $form_state->setErrorByName('email', t('Votre email n\'est lié à aucun compte de l\'espace réservé'));
    }
    else {
      $account =  User::load(reset($ids));
      if (!empty($query = \Drupal::request()->query->get('token'))){
        $hash = $account->get('field_first_connection_hash')->getValue();
        if ($query !== $hash[0]['value']){
          $form_state->setErrorByName('email', t('Votre courriel de correspond pas au lien d\'acces de connection.'));
        }
      }
      $form_state->setValue('user', $account);
    }
  }

  public function submitForm(array &$form,  FormStateInterface $form_state) {
    if (!$account = $form_state->getValue('user')){
      drupal_set_message(t('Une erreur s\'est produite lors de votre demande de nouveau mot de passe.<br>Veuillez réessayer ultérieurement'), 'error');
    }
    else{
      $pwd = $this->create_generated_password();
      $account->setPassword($pwd);
      $account->set('field_surname', $form_state->getValue('surname'));
      $account->set('field_firstname', $form_state->getValue('firstname'));
      $account->set('field_organization', $form_state->getValue('organization'));
      $account->set('field_capacities', $form_state->getValue('capacities'));
      $module = 'ddd_base';
      $key = 'first_connection';
      $from = 'ne-pas-repondre@defenseurdesdroits.fr';
      $to = $account->getEmail();
      $language_code = 'fr';
      $send_now = TRUE;
      $params = [
        'password' => $pwd
      ];
      $result = \Drupal::service('plugin.manager.mail')->mail($module, $key, $to, $language_code, $params, $from, $send_now);
      $account->save();
      drupal_set_message(t('Votre mot de passe vous a été envoyé par email.'), 'success');
    }
    $form_state->setRedirect('ddd_forum.connection');
  }

  private function create_generated_password($length = 16){
    $values = array_merge(range(65, 90), range(97, 122), range(48, 57));
    $max = count($values) - 1;
    $pwd = chr(mt_rand(97, 122));
    for ($i = 1; $i < $length; $i++) {
      $pwd .= chr($values[mt_rand(0, $max)]);
    }
    return $pwd;
  }

}
