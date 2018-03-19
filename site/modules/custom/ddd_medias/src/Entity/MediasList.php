<?php
  /**
  * @file Contains \Drupal\ddd_medias\Entity\MediasList
  */
  namespace Drupal\ddd_medias\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityBase;

  /**
   * @ConfigEntityType(
   *  id ="medias_list",
   *  label = @Translation("Liste de Médias"),
   *  handlers = {
   *    "list_builder" = "Drupal\ddd_medias\MediasListListBuilder",
   *    "form" = {
   *      "default" = "Drupal\ddd_medias\MediasListForm",
   *      "add" = "Drupal\ddd_medias\MediasListForm",
   *      "edit" = "Drupal\ddd_medias\MediasListForm",
   *      "delete" = "Drupal\Core\Entity\EntityDeleteForm"
   *    }
   *  },
   *  config_prefix = "medias_list",
   *  entity_keys = {
   *    "id" = "id",
   *    "label" = "label"
   *  },
   *  links = {
   *     "delete-form" = "/admin/content/medias_list/{medias_list}/delete",
   *     "edit-form" = "/admin/content/medias_list/{medias_list}/edit",
   *     "collection" = "/admin/content/medias_list",
   *  },
   *  config_export = {
   *    "id",
   *    "label",
   *    "teaser",
   *  }
   * )
   */
  class MediasList extends ConfigEntityBase implements MediasListInterface {
    /**
    * The teaser
    *
    * @var string
    */
    protected $teaser;

    /**
    * {@inheritdoc|}
    */
    public function getTeaser() {
      return $this->teaser;
    }
  }
