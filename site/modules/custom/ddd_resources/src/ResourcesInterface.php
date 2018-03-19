<?php

namespace Drupal\ddd_resources;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Resources entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup ddd_resources
 */
interface ResourcesInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
