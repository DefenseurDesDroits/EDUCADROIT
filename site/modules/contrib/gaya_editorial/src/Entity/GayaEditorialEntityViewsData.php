<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\Entity\GayaEditorialEntity.
 */

namespace Drupal\gaya_editorial\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Gaya editorial entity entities.
 */
class GayaEditorialEntityViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['gaya_editorial_entity']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Gaya editorial entity'),
      'help' => $this->t('The Gaya editorial entity ID.'),
    );

    return $data;
  }

}
