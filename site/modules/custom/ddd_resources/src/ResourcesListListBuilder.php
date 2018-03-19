<?php
  /**
   * @file
   * Contains \Drupal\ddd_resources\ResourcesListListBuilder.
   */
  namespace Drupal\ddd_resources;
  use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
  use Drupal\Core\Entity\EntityListBuilder;
  use Drupal\ddd_resources\Entity\ResourcesListInterface;

  class ResourcesListListBuilder extends ConfigEntityListBuilder{
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
    public function buildRow(ResourcesListInterface $entity) {
       $row['label'] = $entity->label();
       return $row + parent::buildRow($entity);
    }
  }
