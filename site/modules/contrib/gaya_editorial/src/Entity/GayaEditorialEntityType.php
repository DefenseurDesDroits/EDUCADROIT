<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\Entity\GayaEditorialEntityType.
 */

namespace Drupal\gaya_editorial\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\gaya_editorial\GayaEditorialEntityTypeInterface;

/**
 * Defines the Gaya editorial entity type entity.
 *
 * @ConfigEntityType(
 *   id = "gaya_editorial_entity_type",
 *   label = @Translation("Gaya editorial entity type"),
 *   handlers = {
 *     "list_builder" = "Drupal\gaya_editorial\GayaEditorialEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\gaya_editorial\Form\GayaEditorialEntityTypeForm",
 *       "edit" = "Drupal\gaya_editorial\Form\GayaEditorialEntityTypeForm",
 *       "delete" = "Drupal\gaya_editorial\Form\GayaEditorialEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\gaya_editorial\GayaEditorialEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "gaya_editorial_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "gaya_editorial_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/gaya_editorial_entity_type/{gaya_editorial_entity_type}",
 *     "add-form" = "/admin/structure/gaya_editorial_entity_type/add",
 *     "edit-form" = "/admin/structure/gaya_editorial_entity_type/{gaya_editorial_entity_type}/edit",
 *     "delete-form" = "/admin/structure/gaya_editorial_entity_type/{gaya_editorial_entity_type}/delete",
 *     "collection" = "/admin/structure/gaya_editorial_entity_type"
 *   }
 * )
 */
class GayaEditorialEntityType extends ConfigEntityBundleBase implements GayaEditorialEntityTypeInterface {
  /**
   * The Gaya editorial entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Gaya editorial entity type label.
   *
   * @var string
   */
  protected $label;

}
