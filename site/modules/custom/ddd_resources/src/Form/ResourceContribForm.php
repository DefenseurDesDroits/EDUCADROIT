<?php
  /**
   * @file
   * Contains \Drupal\ddd_resources\Form\ResourceContribForm.
   **/
  namespace Drupal\ddd_resources\Form;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\paragraphs\Entity\Paragraph;

  class ResourceContribForm extends FormBase {

    public function getFormId() {
      return 'ddd_resource_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['#theme'] = 'ddd_resource_form';

      $form['surname'] = array(
        '#type' => 'textfield',
        '#title' => t('Nom *'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['firstname'] = array(
        '#type' => 'textfield',
        '#title' => t('Prénom *'),
        '#title_display' => 'invisible',
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
      $form['label'] = array(
        '#type' => 'textfield',
        '#title' => t('Nom de la ressource *'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['author'] = array(
        '#type' => 'textfield',
        '#title' => t('Auteur *'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['year'] = array(
        '#type' => 'textfield',
        '#title' => t('Année *'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['description'] = array(
        '#type' => 'textarea',
        '#title' => t('Descriptif *'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['resource_type'] = array(
        '#type' => 'radios',
        '#title' => t('Indiquer une URL ou télécharger un fichier * :'),
        '#title_display' => 'invisible',
        '#options' => [
          'url' => 'URL',
          'download' => 'Télécharger un fichier',
        ],
        '#theme_wrappers' => [
        ]
      );
      $form['url'] = array(
        '#type' => 'textfield',
        '#title' => t('Url'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );
      $form['file'] = array(
        '#type' => 'managed_file',
        '#title' => t('Choisir le fichier'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ],
        '#upload_location' => 'public://contrib/',
        '#upload_validators' => array(
          'file_validate_extensions' => array('pdf jpg jpeg gif png'),
          'file_validate_size' => array(2*1024*1024), // 2Mo
        ),
      );
      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Soumettre'),
        '#attributes' => [
          'class' => ['btn']
        ]
      );
      honeypot_add_form_protection($form, $form_state, array('honeypot', 'time_restriction'));
      return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
      $surname = $form_state->getValue('surname');
      if (empty($surname)) {
        $form_state->setErrorByName('surname', '<span class="visually-hidden">'.t('Veuillez entrer votre nom').'</span>');
        drupal_set_message(t('Le champ "nom" n\'est pas rempli'), 'error');
      }
      $firstname = $form_state->getValue('firstname');
      if (empty($firstname)) {
        $form_state->setErrorByName('firstname', '<span class="visually-hidden">'.t('Veuillez entrer votre prénom').'</span>');
        drupal_set_message(t('Le champ "prénom" n\'est pas rempli'), 'error');
      }
      $email = $form_state->getValue('email');
      if (empty($email)) {
        $form_state->setErrorByName('email', '<span class="visually-hidden">'.t('Veuillez entrer votre courriel').'</span>');
        drupal_set_message(t('Le champ "courriel" n\'est pas rempli ou contient une erreur'), 'error');
      }
      else if (!valid_email_address($email)) {
        $form_state->setErrorByName('email', '<span class="visually-hidden">'.t('Veuillez renseigner un courriel valide').'</span>');
        drupal_set_message(t('Le champ "courriel" n\'est pas rempli ou contient une erreur'), 'error');
      }
      $label = $form_state->getValue('label');
      if (empty($label)) {
        $form_state->setErrorByName('label', '<span class="visually-hidden">'.t('Veuillez entrer le nom de la ressource').'</span>');
        drupal_set_message(t('Le champ "nom de la ressource" n\'est pas rempli'), 'error');
      }
      $author = $form_state->getValue('author');
      if (empty($author)) {
        $form_state->setErrorByName('author', '<span class="visually-hidden">'.t('Veuillez entrer un auteur').'</span>');
        drupal_set_message(t('Le champ "auteur" n\'est pas rempli'), 'error');
      }
      $year = $form_state->getValue('year');
      if (empty($year)) {
        $form_state->setErrorByName('year', '<span class="visually-hidden">'.t('Veuillez entrer une année').'</span>');
        drupal_set_message(t('Le champ "année" n\'est pas rempli'), 'error');
      }
      else if (empty(intval($year)) || strlen(trim($year)) != 4){
        $form_state->setErrorByName('year', '<span class="visually-hidden">'.t('Veuillez entrer une année valide').'</span>');
        drupal_set_message(t('Le champ "année" n\'est pas valide'), 'error');
      }
      $description = $form_state->getValue('description');
      if (empty($description)) {
        $form_state->setErrorByName('description', '<span class="visually-hidden">'.t('Veuillez entrer un descriptif').'</span>');
        drupal_set_message(t('Le champ "descriptif" n\'est pas rempli'), 'error');
      }
      $resource_type = $form_state->getValue('resource_type');
      $url = $form_state->getValue('url');
      $file = $form_state->getValue('file');
      if (empty($resource_type)) {
        $form_state->setErrorByName('resource_type', '<span class="visually-hidden">'.t('Veuillez selectionner un type de ressource').'</span>');
        drupal_set_message(t('Aucun type de ressource n\'a été selectionné'), 'error');
      }
      else if ($resource_type == 'url' && empty($url)){
        $form_state->setErrorByName('url', '<span class="visually-hidden">'.t('Veuillez entrer une url').'</span>');
        drupal_set_message(t('Le champ "url" n\'est pas rempli'), 'error');
      }
      else if ($resource_type == 'download' && empty($file)){
        $form_state->setErrorByName('file', '<span class="visually-hidden">'.t('Veuillez selectionner un fichier').'</span>');
        drupal_set_message(t('Aucun fichier n\'a été selectionné'), 'error');
      }
    }

    public function submitForm(array &$form,  FormStateInterface $form_state) {
      $values = $form_state->getValues();

      $data = [];
      $entity_manager = \Drupal::entityManager();
      $storage = $entity_manager->getStorage('node');
      $data['type'] = 'resource';
      $data['field_status'] = 'added';
      $paragraph_title = '';
      $paragraph_description = '';
      if (!empty($values['label'])){
        $data['title'] = $values['label'];
        $paragraph_title = $values['label'];
      }
      if (!empty($values['surname'])){
        $data['field_surname'] = $values['surname'];
      }
      if (!empty($values['firstname'])){
        $data['field_firstname'] = $values['firstname'];
      }
      if (!empty($values['email'])){
        $data['field_email'] = $values['email'];
      }
      if (!empty($values['author'])){
        $data['field_author'] = $values['author'];
      }
      if (!empty($values['year'])){
        $data['field_year'] = $values['year'];
      }
      if (!empty($values['description'])){
        $data['field_description'] = $values['description'];
        $paragraph_description = $values['description'];
      }
      if (!empty($values['resource_type'])){
        if ($values['resource_type'] == 'url' && !empty($values['url'])){
          $paragraph = Paragraph::create([
            'type' => 'ressources_link',
            'field_description' => array(
              "value"  =>  $paragraph_description,
              "format" => "text"
            ),
            'field_title' => $paragraph_title,
            'field_links' => array(
              "title"  =>  $paragraph_title,
              "uri" => $values['url']
            ),
          ]);
          $paragraph->save();
          $data['field_paragraphs'] = array(
            array(
              'target_id' => $paragraph->id(),
              'target_revision_id' => $paragraph->getRevisionId(),
            )
          );
        }
        elseif ($values['resource_type'] == 'download' && !empty($values['file'])){
          $file = file_load($values['file'][0]);
          if (!empty($file)){
            $uri = $file->getFileUri();
            $title = $file->get('filename')->value;
            $paragraph = Paragraph::create([
              'type' => 'ressources_link',
              'field_description' => array(
                "value"  =>  $paragraph_description,
                "format" => "text"
              ),
              'field_title' => $paragraph_title,
              'field_links' => array(
                "title"  =>  $title,
                "uri" => str_replace('public:/', 'internal:/sites/default/files', $uri)
              ),
            ]);
            $paragraph->save();
            $data['field_paragraphs'] = array(
              array(
                'target_id' => $paragraph->id(),
                'target_revision_id' => $paragraph->getRevisionId(),
              )
            );
          }
        }
      }
      $page = $storage->create($data);
      $page->save();
    }
  }
