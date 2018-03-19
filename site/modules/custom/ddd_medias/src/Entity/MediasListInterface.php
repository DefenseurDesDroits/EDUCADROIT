<?php
   /**
    * @file Contains \Drupal\ddd_medias\Entity\MediasListInterface.
    */
  namespace Drupal\ddd_medias\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityInterface;

  interface MediasListInterface  extends ConfigEntityInterface{
    /**
     * Gets the teaser value.
     *
     * @return string
     */
    public function getTeaser();
  }
