<?php
/**
 * @file
 * Contains \Drupal\ddd_contact\Controller\ContactController class.
 */
namespace Drupal\ddd_contact\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for My Module module.
 */
class ContactController extends ControllerBase {

  /**
   * \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityManager;

  /**
   * \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * Returns markup for our custom page.
   */
  public function contactPage() {
    $storage = $this->entityManager->getStorage('contactconfig');

    if (!$page = $storage->load('contact-page')){
      $title = 'CONTACTEZ-NOUS';
      $teaser = '';
      $data = array(
        'label' => $title,
        'id' => 'contact-page'
      );
      $page = $storage->create($data);
      $page->save();
    }
    else {
      $title = $page->label;
      $teaser = $page->getTeaser();
    }

    // getting filter form
    $form = $this->formBuilder->getForm('Drupal\ddd_contact\Form\ContactPageForm');

    $build = [
      '#type' => 'content',
      '#theme' => 'ddd_contact_page',
      '#title' => $title,
      '#teaser' => $teaser,
      '#form' => $form,
    ];
    return $build;
  }

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param Drupal\Core\Entity\EntityTypeManager $plugin_id
   *   The entity type manager
   * @param Drupal\Core\Form\FormBuilder $form_builder
   *   The form builder
   */
  public function __construct(
    EntityTypeManager $entity_type_manager,
    FormBuilder $form_builder) {

    $this->entityManager = $entity_type_manager;
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('form_builder')
    );
  }
}
