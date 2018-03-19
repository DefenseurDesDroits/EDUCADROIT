<?php
   /**
    * @file Contains \Drupal\ddd_resources\Entity\ResourcesListInterface.
    */
  namespace Drupal\ddd_resources\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityInterface;

  interface ResourcesListInterface  extends ConfigEntityInterface{
    /**
     * Gets the teaser value.
     *
     * @return string
     */
    public function getTeaser();
    /**
     * Gets the email value.
     *
     * @return string
     */
    public function getEmail();
  }
