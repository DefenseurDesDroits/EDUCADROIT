<?php

namespace Drupal\ddd_glossary;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Glossary entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup ddd_glossary
 */
interface GlossaryInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
