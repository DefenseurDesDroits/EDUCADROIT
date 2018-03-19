<?php

namespace Drupal\ddd_speakers;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Referrer entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup ddd_speakers
 */
interface ReferrerInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
