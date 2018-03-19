<?php
   /**
    * @file Contains \Drupal\ddd_config\Entity\ConfigInterface.
    */
  namespace Drupal\ddd_config\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityInterface;

  interface ConfigInterface  extends ConfigEntityInterface{
    /**
     * Gets the teaser value.
     *
     * @return string
     */
    public function getFooter();
  }
