<?php

namespace Drupal\ddd_newsletter\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a ddd_newsletter entity.
 *
 * @ingroup ddd_newsletter
 */
class NewsletterDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %name?', array('%name' => $this->entity->get('field_email')->value));
  }

  /**
   * {@inheritdoc}
   *
   * If the delete command is canceled, return to the newsletter list.
   */
  public function getCancelUrl() {
    return new Url('entity.newsletter.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   *
   * Delete the entity and log the event. logger() replaces the watchdog.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    $this->logger('ddd_newsletter')->notice('@type: deleted %title.',
      array(
        '@type' => $this->entity->bundle(),
        '%title' => $this->entity->get('field_email')->value,
      ));
    $form_state->setRedirect('entity.newsletter.collection');
  }

}
