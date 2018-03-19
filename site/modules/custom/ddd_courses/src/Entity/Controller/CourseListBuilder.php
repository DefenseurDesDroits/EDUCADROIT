<?php

namespace Drupal\ddd_courses\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\file\Entity\File;

/**
 * Provides a list controller for ddd_courses entity.
 *
 * @ingroup ddd_courses
 */
class CourseListBuilder extends EntityListBuilder {

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;


  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('url_generator')
    );
  }

  /**
   * Constructs a new CourseListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The url generator.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator) {
    parent::__construct($entity_type, $storage);
    $this->urlGenerator = $url_generator;
  }


  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = array(
      '#markup' => $this->t('Content Entity implements a Course model. These course are fieldable entities. You can manage the fields on the <a href="@adminlink">Course admin page</a>.', array(
        '@adminlink' => $this->urlGenerator->generateFromRoute('ddd_courses.course_settings'),
      )),
    );
    $build['table'] = parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the course list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['image'] = $this->t('');
    $header['label'] = $this->t('Titre');
    $header['link'] = $this->t('Lien');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ddd_courses\Entity\Course */
    $image = $entity->field_image;
    if (!empty($image)){
      foreach ($image as $value){
        $file = File::load($value->target_id);
      }
      if (!empty($file)){
        $uri = $file->getFileUri();
        // $image_url = ImageStyle::load('thumbnail')->buildUrl($uri);
        $image = [
          '#theme' => 'image_style',
          '#width' => '100',
          '#height' => '100',
          '#style_name' => 'thumbnail',
          '#uri' => $uri,
        ];
        $row['image'] = render($image);
      }
    }
    $row['label'] = $entity->label();
    if (!empty($entity->field_link)){
      foreach ( $entity->field_link as $value){
        $link = $value;
      }
      if (!empty($link)){
        $row['link'] = $link->title .' : ' . $link->uri;
      }
    }
    return $row + parent::buildRow($entity);
  }

}
