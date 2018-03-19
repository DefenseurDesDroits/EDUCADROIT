<?php
  /**
  * @file Contains \Drupal\ddd_courses\Entity\CoursesList
  */
  namespace Drupal\ddd_courses\Entity;
  use Drupal\Core\Config\Entity\ConfigEntityBase;

  /**
   * @ConfigEntityType(
   *  id ="courses_list",
   *  label = @Translation("Liste de Parcours"),
   *  handlers = {
   *    "list_builder" = "Drupal\ddd_courses\CoursesListListBuilder",
   *    "form" = {
   *      "default" = "Drupal\ddd_courses\CoursesListForm",
   *      "add" = "Drupal\ddd_courses\CoursesListForm",
   *      "edit" = "Drupal\ddd_courses\CoursesListForm",
   *      "delete" = "Drupal\Core\Entity\EntityDeleteForm"
   *    }
   *  },
   *  config_prefix = "courses_list",
   *  entity_keys = {
   *    "id" = "id",
   *    "label" = "label"
   *  },
   *  links = {
   *     "delete-form" = "/admin/content/courses_list/{courses_list}/delete",
   *     "edit-form" = "/admin/content/courses_list/{courses_list}/edit",
   *     "collection" = "/admin/content/courses_list",
   *  },
   *  config_export = {
   *    "id",
   *    "label",
   *    "teaser",
   *  }
   * )
   */
  class CoursesList extends ConfigEntityBase implements CoursesListInterface {
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
