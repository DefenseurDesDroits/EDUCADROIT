<?php
  /**
  * @file Contains \Drupal\ddd_config\Entity\Config
  */
  namespace Drupal\ddd_config\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityBase;

  /**
   * @ConfigEntityType(
   *  id ="config",
   *  label = @Translation("Configurations"),
   *  handlers = {
   *    "list_builder" = "Drupal\ddd_config\ConfigListBuilder",
   *    "form" = {
   *      "default" = "Drupal\ddd_config\ConfigForm",
   *      "add" = "Drupal\ddd_config\ConfigForm",
   *      "edit" = "Drupal\ddd_config\ConfigForm",
   *      "delete" = "Drupal\Core\Entity\EntityDeleteForm"
   *    }
   *  },
   *  config_prefix = "config",
   *  entity_keys = {
   *    "id" = "id",
   *    "label" = "label"
   *  },
   *  links = {
   *     "delete-form" = "/admin/content/config/{config}/delete",
   *     "edit-form" = "/admin/content/config/{config}/edit",
   *     "collection" = "/admin/content/config",
   *  },
   *  config_export = {
   *    "id",
   *    "label",
   *    "header_ddd_logo",
   *    "footer_follow_title",
   *    "footer_facebook_link",
   *    "footer_twitter_link",
   *    "footer_ddd_link",
   *    "footer_ddd_logo",
   *    "footer_ddd_description",
   *    "ddd_cookie_disclaimer",
   *  }
   * )
   */
  class Config extends ConfigEntityBase implements ConfigInterface {
    /**
    * The header_ddd_logo
    *
    * @var string
    */
    protected $header_ddd_logo;
    /**
    * The footer_follow_title
    *
    * @var string
    */
    protected $footer_follow_title;
    /**
    * The footer_facebook_link
    *
    * @var link
    */
    protected $footer_facebook_link;
    /**
    * The footer_twitter_link
    *
    * @var link
    */
    protected $footer_twitter_link;
    /**
    * The footer_ddd_link
    *
    * @var string
    */
    protected $footer_ddd_link;
    /**
    * The footer_ddd_logo
    *
    * @var string
    */
    protected $footer_ddd_logo;
    /**
    * The footer_ddd_description
    *
    * @var string
    */
    protected $footer_ddd_description;
    /**
    * The ddd_cookie_disclaimer
    *
    * @var string
    */
    protected $ddd_cookie_disclaimer;

    /**
    * {@inheritdoc|}
    */
    public function getFooter() {
      $footer = [];
      $footer['follow_title'] = $this->footer_follow_title;
      $footer['facebook_link'] = $this->footer_facebook_link;
      $footer['twitter_link'] = $this->footer_twitter_link;
      $footer['ddd_link'] = $this->footer_ddd_link;
      $footer['ddd_logo'] = $this->footer_ddd_logo;
      $footer['ddd_description'] = $this->footer_ddd_description;
      return $footer;
    }
    /**
    * {@inheritdoc|}
    */
    public function getHeader() {
      $header = [];
      $header['ddd_logo'] = $this->header_ddd_logo;
      $header['ddd_cookie_disclaimer'] = $this->ddd_cookie_disclaimer;
      return $header;
    }
  }
