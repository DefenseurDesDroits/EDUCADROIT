<?php
   /**
    * @file Contains \Drupal\ddd_courses\Entity\CoursesListInterface.
    */
  namespace Drupal\ddd_courses\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityInterface;

  interface CoursesListInterface  extends ConfigEntityInterface{
    /**
     * Gets the teaser value.
     *
     * @return string
     */
    public function getTeaser();
  }
