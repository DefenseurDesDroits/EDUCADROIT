<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\GayaEditorialEntityAccessControlHandler.
 */

namespace Drupal\gaya_editorial;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Gaya editorial entity entity.
 *
 * @see \Drupal\gaya_editorial\Entity\GayaEditorialEntity.
 */
class GayaEditorialEntityAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\gaya_editorial\GayaEditorialEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished gaya editorial entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published gaya editorial entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit gaya editorial entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete gaya editorial entity entities');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add gaya editorial entity entities');
  }

}
