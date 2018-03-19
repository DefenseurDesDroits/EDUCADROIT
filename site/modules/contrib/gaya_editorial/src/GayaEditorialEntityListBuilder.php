<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\GayaEditorialEntityListBuilder.
 */

namespace Drupal\gaya_editorial;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Gaya editorial entity entities.
 *
 * @ingroup gaya_editorial
 */
class GayaEditorialEntityListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Gaya editorial entity ID');
    $header['name'] = $this->t('Title backoffice');
    $header['author'] = $this->t('Author name');
    $header['created'] = $this->t('Created date');
    $header['changed'] = $this->t('Updated date');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\gaya_editorial\Entity\GayaEditorialEntity */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.gaya_editorial_entity.edit_form', array(
          'gaya_editorial_entity' => $entity->id(),
        )
      )
    );
    $row['author'] = $entity->getOwner()->getAccountName();
    $row['created'] = format_date($entity->getCreatedTime());
    $row['changed'] = format_date($entity->getChangedTime());
    return $row + parent::buildRow($entity);
  }

}
