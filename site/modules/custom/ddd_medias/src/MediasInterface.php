<?php

namespace Drupal\ddd_medias;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Medias entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup ddd_medias
 */
interface MediasInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
