<?php
  /**
   * @file
   * Contains \Drupal\ddd_config\ConfigListBuilder.
   */
  namespace Drupal\ddd_config;
  use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
  use Drupal\Core\Entity\EntityListBuilder;
  use Drupal\ddd_config\Entity\ConfigInterface;

  class ConfigListBuilder extends ConfigEntityListBuilder{
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
    public function buildRow(ConfigInterface $entity) {
       $row['label'] = $entity->label();
       return $row + parent::buildRow($entity);
    }
  }
