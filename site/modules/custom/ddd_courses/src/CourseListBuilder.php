<?php
  /**
   * @file
   * Contains \Drupal\ddd_courses\CourseListBuilder.
   */
  namespace Drupal\ddd_courses;
  use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
  use Drupal\Core\Entity\EntityListBuilder;
  use Drupal\ddd_courses\Entity\CourseInterface;

  class CourseListBuilder extends ConfigEntityListBuilder{
    /**
     * {@inheritdoc}
     */
    public function buildHeader() {
      $header['label'] = t('Label');
      return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(CourseInterface $entity) {
       $row['label'] = $entity->label();
       return $row + parent::buildRow($entity);
    }
  }
