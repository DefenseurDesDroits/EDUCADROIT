<?php

namespace Drupal\ddd_base;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Home entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup ddd_base
 */
interface HomeInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
