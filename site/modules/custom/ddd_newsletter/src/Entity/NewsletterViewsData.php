<?php
/**
 * @file
 * Contains \Drupal\ddd_newsletter\Entity\NewsletterViewsData.
 */
namespace Drupal\ddd_newsletter\Entity;
use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;
/**
 * Provides Views data for Folder entities.
 */
class NewsletterViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['newsletter']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Newsletter'),
      'help' => $this->t('Newsletter'),
    );
    return $data;
  }
}
