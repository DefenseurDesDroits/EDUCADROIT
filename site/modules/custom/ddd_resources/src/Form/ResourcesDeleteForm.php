<?php

namespace Drupal\ddd_resources\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a ddd_resources entity.
 *
 * @ingroup ddd_resources
 */
class ResourcesDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete entity %name?', array('%name' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   *
   * If the delete command is canceled, return to the resources list.
   */
  public function getCancelUrl() {
    return new Url('entity.resources.collection');
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

    $this->logger('ddd_resources')->notice('@type: deleted %title.',
      array(
        '@type' => $this->entity->bundle(),
        '%title' => $this->entity->label(),
      ));
    $form_state->setRedirect('entity.resources.collection');
  }

}
