<?php
  /**
   * @file
   * Contains \Drupal\ddd_newsletter\Form\NewsletterBlockForm.
   **/
  namespace Drupal\ddd_newsletter\Form;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  class NewsletterBlockForm extends FormBase {

    public function getFormId() {
      return 'ddd_newsletter_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state, $arg1 = NULL, $arg2 = NULL) {
      $form['#theme'] = 'ddd_newsletter_form';

      $form['email'] = array(
        '#type' => 'textfield',
        '#title' => (!empty($arg1)) ? $arg1 . '*' : t('Courriel*'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );

      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => ((!empty($arg2)) ? $arg2 : t('s\'inscrire')),
        '#attributes' => [
          'class' => ['btn']
        ]
      );
      honeypot_add_form_protection($form, $form_state, array('honeypot', 'time_restriction'));
      return $form;
    }

    public function validateForm(array &$form,  FormStateInterface $form_state) {
      $email = $form_state->getValue('email');
      if (empty($email)) {
        $form_state->setErrorByName('email', t('Veuillez renseigner votre courriel pour vous inscrire'));
      }
      if (!\Drupal::service('email.validator')->isValid($email)){
        $form_state->setErrorByName('email', t('Veuillez renseigner un courriel valide pour vous inscrire'));
      }
    }

    public function submitForm(array &$form,  FormStateInterface $form_state) {
      $values = $form_state->getValues();

      $query = \Drupal::entityQuery('newsletter');
      $query->condition('field_email', $values['email']);
      $ids = $query->execute();
      if(empty($ids)){
        $entity_manager = \Drupal::entityManager();
        $storage = $entity_manager->getStorage('newsletter');

        $data = [];
        if (!empty($values['email'])){
          $data['field_email'] = $values['email'];
          $data['label'] = $values['email'];
        }
        $page = $storage->create($data);
        $page->save();
        drupal_set_message(t("Vous êtes maintenant abonné(e) à la newsletter d'Educadroit par le Défenseur des droits.<br>
          Nous vous remercions,<br>
          L'équipe du Défenseur des droits"));
      }
      else {
        drupal_set_message(t('Vous êtes déjà abonné(e) à la newsletter de l\'association sur cette adresse email.'));
      }
    }
  }
