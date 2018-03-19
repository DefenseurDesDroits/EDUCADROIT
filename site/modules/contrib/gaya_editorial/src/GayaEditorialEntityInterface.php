<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\GayaEditorialEntityInterface.
 */

namespace Drupal\gaya_editorial;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Gaya editorial entity entities.
 *
 * @ingroup gaya_editorial
 */
interface GayaEditorialEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Gaya editorial entity type.
   *
   * @return string
   *   The Gaya editorial entity type.
   */
  public function getType();

  /**
   * Gets the Gaya editorial entity name.
   *
   * @return string
   *   Name of the Gaya editorial entity.
   */
  public function getName();

  /**
   * Sets the Gaya editorial entity name.
   *
   * @param string $name
   *   The Gaya editorial entity name.
   *
   * @return \Drupal\gaya_editorial\GayaEditorialEntityInterface
   *   The called Gaya editorial entity entity.
   */
  public function setName($name);

  /**
   * Gets the Gaya editorial entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Gaya editorial entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Gaya editorial entity creation timestamp.
   *
   * @param int $timestamp
   *   The Gaya editorial entity creation timestamp.
   *
   * @return \Drupal\gaya_editorial\GayaEditorialEntityInterface
   *   The called Gaya editorial entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Gaya editorial entity published status indicator.
   *
   * Unpublished Gaya editorial entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Gaya editorial entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Gaya editorial entity.
   *
   * @param bool $published
   *   TRUE to set this Gaya editorial entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\gaya_editorial\GayaEditorialEntityInterface
   *   The called Gaya editorial entity entity.
   */
  public function setPublished($published);

}
