<?php
  /**
   * @file
   * Contains \Drupal\ddd_contact\ContactConfigListBuilder.
   */
  namespace Drupal\ddd_contact;
  use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
  use Drupal\Core\Entity\EntityListBuilder;
  use Drupal\ddd_contact\Entity\CContactConfigInterface;

  class ContactConfigListBuilder extends ConfigEntityListBuilder{
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
    public function buildRow(ContactConfigInterface $entity) {
       $row['label'] = $entity->label();
       // $row['actions'] = '<a href="'
       // $row['actions'] =
       return $row + parent::buildRow($entity);
    }
  }
