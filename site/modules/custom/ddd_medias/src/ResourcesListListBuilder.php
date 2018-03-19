<?php
  /**
   * @file
   * Contains \Drupal\ddd_medias\MediasListListBuilder.
   */
  namespace Drupal\ddd_medias;
  use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
  use Drupal\Core\Entity\EntityListBuilder;
  use Drupal\ddd_medias\Entity\MediasListInterface;

  class MediasListListBuilder extends ConfigEntityListBuilder{
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
    public function buildRow(MediasListInterface $entity) {
       $row['label'] = $entity->label();
       return $row + parent::buildRow($entity);
    }
  }
