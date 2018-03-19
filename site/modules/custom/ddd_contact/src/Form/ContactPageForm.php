<?php
  /**
   * @file
   * Contains \Drupal\ddd_contact\Form\ContactPageForm.
   **/
  namespace Drupal\ddd_contact\Form;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Component\Utility\Unicode;
  use Drupal\Core\Entity\EntityForm;
  use Drupal\Core\Language\LanguageInterface;

  class ContactPageForm extends FormBase {

    public function getFormId() {
      return 'ddd_contact_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['#theme'] = 'ddd_contact_form';

      $form['surname'] = array(
        '#type' => 'textfield',
        '#title' => t('Nom'),
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['firstname'] = array(
        '#type' => 'textfield',
        '#title' => t('Prénom'),
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['email'] = array(
        '#type' => 'textfield',
        '#title' => t('Courriel (nom@exemple.fr) *'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );

      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', "contact_object");
      $query->sort('weight');
      $tids = $query->execute();
      $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
      $options = [];
      foreach ($terms as $key => $value) {
        $options[$key] = $value->toLink()->getText();
      }
      $form['subject'] = array(
        '#type' => 'select',
        '#title' => t('Objet *'),
        '#empty_option' => t('Sélectionner l’objet de votre demande'),
        '#options' => $options
      );

      $form['message'] = array(
        '#type' => 'textarea',
        '#title' => t('Votre message (500 caractères max.) *'),
        '#attributes' => [
          'class' => ['form-control']
        ]
      );

      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Envoyer'),
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
        $form_state->setErrorByName('email', '<span class="visually-hidden">'.t('Veuillez entrer votre courriel').'</span>');
        drupal_set_message(t('Le champ "courriel" n\'est pas rempli ou contient une erreur'), 'error');
      }
      else if (!valid_email_address($email)) {
        $form_state->setErrorByName('email', '<span class="visually-hidden">'.t('Veuillez renseigner un courriel valide').'</span>');
        drupal_set_message(t('Le champ "courriel" n\'est pas rempli ou contient une erreur'), 'error');
      }
      $subject = $form_state->getValue('subject');
      if (empty($subject)) {
        $form_state->setErrorByName('subject', '<span class="visually-hidden">'.t('Veuillez sélectionner un objet pour votre message').'</span>');
        drupal_set_message(t('Aucun object n\'a été sélectionné'), 'error');
      }
      $message = $form_state->getValue('message');
      if (empty($message)) {
        $form_state->setErrorByName('message', '<span class="visually-hidden">'.t('Veuillez rédiger votre message').'</span>');
        drupal_set_message(t('Le champ "Message" n\'est pas rempli ou contient une erreur'), 'error');
      }
      else if (strlen($message) > 500){
        $form_state->setErrorByName('message', '<span class="visually-hidden">'.t('Votre message ne peut pas excéder 500 caractères, espaces compris').'</span>');
        drupal_set_message(t('Le champ "Message" n\'est pas rempli ou contient une erreur'), 'error');
      }
    }

    public function submitForm(array &$form,  FormStateInterface $form_state) {
      $values = $form_state->getValues();
      // data saving

      $entity_manager = \Drupal::entityManager();
      $storage = $entity_manager->getStorage('contact');
      $data = [];
      if (!empty($values['surname'])){
        $data['field_surname'] = $values['surname'];
      }
      if (!empty($values['firstname'])){
        $data['field_firstname'] = $values['firstname'];
      }
      if (!empty($values['email'])){
        $data['field_email'] = $values['email'];
        $data['label'] = $values['email'];
      }
      if (!empty($values['subject'])){
        $data['field_subject'] = $values['subject'];
      }
      if (!empty($values['message'])){
        $data['field_message'] = $values['message'];
      }
      $page = $storage->create($data);
      $page->save();

      // set params
      $params = $values;
      $term = \Drupal\taxonomy\Entity\Term::load($params['subject']);
      $params['subject'] = $term->toLink()->getText();

      // mail sending
      $module = 'ddd_base';
      $key = 'contact_message';
      $from = $values['email'];
      $storage = $entity_manager->getStorage('contactconfig');
      if ($page = $storage->load('contact-page')){
        $to = $page->getEmail();
      }
      if (empty($to)){
        $to = $this->config('system.site')->get('mail');
      }
      $language_code = 'fr';
      $send_now = TRUE;
      $result = \Drupal::service('plugin.manager.mail')->mail($module, $key, $to, $language_code, $params, $from, $send_now);
      if ($result['result'] == TRUE) {
        drupal_set_message(t('Votre demande de contact a bien été transmise et nous allons y faire suite dans les meilleurs délais.<br>
          Nous vous remercions de votre intérêt pour notre site.<br>
          L\'équipe du Défenseur des droits'));
      }
      else {
        drupal_set_message(t('Une erreur s\'est produite lors de votre demande de contact.<br>Veuillez réessayer ultérieurement'), 'error');
      }
    }
  }
