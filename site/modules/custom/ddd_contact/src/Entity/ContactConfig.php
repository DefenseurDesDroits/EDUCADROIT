<?php
  /**
  * @file Contains \Drupal\ddd_contact\Entity\ContactConfig
  */
  namespace Drupal\ddd_contact\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityBase;

  /**
   * @ConfigEntityType(
   *  id ="contactconfig",
   *  label = @Translation("ContactConfig"),
   *  handlers = {
   *    "list_builder" = "Drupal\ddd_contact\ContactConfigListBuilder",
   *    "form" = {
   *      "default" = "Drupal\ddd_contact\ContactConfigForm",
   *      "add" = "Drupal\ddd_contact\ContactConfigForm",
   *      "edit" = "Drupal\ddd_contact\ContactConfigForm",
   *      "delete" = "Drupal\Core\Entity\EntityDeleteForm"
   *    }
   *  },
   *  config_prefix = "contactconfig",
   *  entity_keys = {
   *    "id" = "id",
   *    "label" = "label"
   *  },
   *  links = {
   *     "delete-form" = "/admin/content/contactconfig/{contactconfig}/delete",
   *     "edit-form" = "/admin/content/contactconfig/{contactconfig}/edit",
   *     "collection" = "/admin/content/contactconfig",
   *  },
   *  config_export = {
   *    "id",
   *    "label",
   *    "teaser",
   *    "email",
   *  }
   * )
   */
  class ContactConfig extends ConfigEntityBase implements ContactConfigInterface {
    /**
    * The contact's teaser.
    *
    * @var string
    */
    protected $teaser;

    /**
    * The contact's email.
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
