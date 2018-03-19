<?php
  /**
  * @file Contains \Drupal\ddd_resources\Entity\ResourcesList
  */
  namespace Drupal\ddd_resources\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityBase;

  /**
   * @ConfigEntityType(
   *  id ="resources_list",
   *  label = @Translation("Liste de Ressource"),
   *  handlers = {
   *    "list_builder" = "Drupal\ddd_resources\ResourcesListListBuilder",
   *    "form" = {
   *      "default" = "Drupal\ddd_resources\ResourcesListForm",
   *      "add" = "Drupal\ddd_resources\ResourcesListForm",
   *      "edit" = "Drupal\ddd_resources\ResourcesListForm",
   *      "delete" = "Drupal\Core\Entity\EntityDeleteForm"
   *    }
   *  },
   *  config_prefix = "resources_list",
   *  entity_keys = {
   *    "id" = "id",
   *    "label" = "label"
   *  },
   *  links = {
   *     "delete-form" = "/admin/content/resources_list/{resources_list}/delete",
   *     "edit-form" = "/admin/content/resources_list/{resources_list}/edit",
   *     "collection" = "/admin/content/resources_list",
   *  },
   *  config_export = {
   *    "id",
   *    "label",
   *    "teaser",
   *    "email",
   *  }
   * )
   */
  class ResourcesList extends ConfigEntityBase implements ResourcesListInterface {
    /**
    * The teaser
    *
    * @var string
    */
    protected $teaser;

    /**
    * The email
    *
    * @var string
    */
    protected $email;

    /**
    * {@inheritdoc|}
    */
    public function getTeaser() {
      return $this->teaser;
    }

    /**
    * {@inheritdoc|}
    */
    public function getEmail() {
      return $this->email;
    }
  }
