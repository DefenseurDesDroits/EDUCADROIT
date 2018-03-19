<?php

namespace Drupal\ddd_courses;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Course entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup ddd_courses
 */
interface CourseInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
