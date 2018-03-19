<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Form\RetrievePasswordForm.
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

class RetrievePasswordForm extends FormBase {

  public function getFormId() {
    return 'ddd_retrieve_password_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#theme'] = 'ddd_retrieve_password_form';

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#required' => true,
      '#attributes' => [
        'class' => ['form-control'],
      ]
    );

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
    if (empty($email = $form_state->getValue('email'))){
      $form_state->setErrorByName('email', t('Veuillez renseigner votre courriel'));
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
      drupal_set_message(t('Une erreur s\'est produite lors de votre inscription.<br>Veuillez réessayer ultérieurement'), 'error');
    }
    $pwd = $this->create_generated_password();
    $account->setPassword($pwd);
    $account->set('field_first_connection_hash', '');
    // mail sending
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
    if ($result['result'] == TRUE) {
      drupal_set_message(t('Nous venons de vous envoyer une email contenant votre mot de passe. Vous pouvez dès à présent vous connecter
        sur <a href="' . Url::fromRoute('ddd_forum.connection')->toString() . '">notre espace</a>.'));
      $account->save();
    }
    else {
      drupal_set_message(t('Une erreur s\'est produite lors de votre inscription.<br>Veuillez réessayer ultérieurement'), 'error');
    }
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
