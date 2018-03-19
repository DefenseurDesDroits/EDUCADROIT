<?php
   /**
    * @file Contains \Drupal\ddd_contact\Entity\ContactConfigInterface.
    */
  namespace Drupal\ddd_contact\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityInterface;

  interface ContactConfigInterface  extends ConfigEntityInterface{
    /**
     * Gets the message value.
     *
     * @return string
     */
    public function getTeaser();

    /**
     * Gets the message value.
     *
     * @return string
     */
    public function getEmail();
  }
