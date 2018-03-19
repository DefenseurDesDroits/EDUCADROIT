<?php
  /**
   * @file
   * Contains \Drupal\ddd_courses\CoursesListListBuilder.
   */
  namespace Drupal\ddd_courses;
  use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
  use Drupal\Core\Entity\EntityListBuilder;
  use Drupal\ddd_courses\Entity\CoursesListInterface;

  class CoursesListListBuilder extends ConfigEntityListBuilder{
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
    public function buildRow(CoursesListInterface $entity) {
       $row['label'] = $entity->label();
       return $row + parent::buildRow($entity);
    }
  }
