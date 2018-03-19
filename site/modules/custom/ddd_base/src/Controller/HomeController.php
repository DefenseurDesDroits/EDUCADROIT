<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Controller\HomeController class.
 */
  namespace Drupal\ddd_base\Controller;
  use Drupal\Core\Controller\ControllerBase;
  use Drupal\image\Entity\ImageStyle;
  use Drupal\Core\Url;
  use Drupal\Core\Entity;
  use Drupal\file\Entity\File;
  /**
   * Returns responses for My Module module.
   */
  class HomeController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function homePage() {

      $about = [];
      if (!$page = entity_load('home', 1)){
        $data = array(
          'field_label' => $title,
          'id' => 1
        );
        $page = entity_create('home', $data);
        $page->save();
      }
      else {
        $image = [];
        if (!empty($page->field_image_about)){
          foreach ($page->field_image_about as $value){
            $file = File::load($value->target_id);
          }
          $uri = $file->getFileUri();
          $image['url'] = ImageStyle::load('1600x480')->buildUrl($uri);
          $image['alt'] = $value->alt;
          $image['title'] = $value->title;
        }
        $link = [];
        if (!empty($page->field_link_about)){
          global $base_url;
          foreach ($page->field_link_about as $value){
            if (empty($link['title'] = $value->title)){
              $link['title'] = 'Découvrir';
            }
            $link['url'] = str_replace('internal:', $base_url, $value->uri);
          }
        }
        $about = [
          'title' => $page->field_title_about->value,
          'teaser' => $page->field_teaser_about->value,
          'image' => $image,
          'link' => $link,
        ];
        $resources = [
          'title' => (!empty($page->field_title_resources->value)?$page->field_title_resources->value:'Centre de ressources'),
          'teaser' => $page->field_teaser_resources->value,
          'form' => \Drupal::formBuilder()->getForm('Drupal\ddd_resources\Form\FilterResourcesForm', 'Rechercher')
        ];
        $courses = [];
        if (!empty($page->field_courses)){
          foreach($page->field_courses as $key => $value){
            $entity = entity_load('course', $value->target_id);
            $courses[] = entity_view($entity, 'full');
          }
        }
        $speakers = [
          'title' => (!empty($page->field_title_speakers->value)?$page->field_title_speakers->value:'Trouver un intervenant'),
          'teaser' => $page->field_teaser_speakers->value,
          'form' => \Drupal::formBuilder()->getForm('Drupal\ddd_speakers\Form\FilterSpeakersForm')
        ];
        $collection = [];
        if (!empty($page->field_resources)){
          foreach($page->field_resources as $key => $value){
            $entity = node_load($value->target_id);
            if ($entity)
              $collection[] = entity_view($entity, 'small_teaser');
          }
        }
        $resources_list = [
          'title' => $page->field_title_resources_list->value,
          'teaser' => $page->field_teaser_resources_list->value,
          'collection' => $collection
        ];
        $collection = [];
        if (!empty($page->field_spotlights)){
          foreach($page->field_spotlights as $value){
            $entity = entity_load('paragraph', $value->target_id);
            $collection[] = entity_view($entity, 'full');
          }
        }
        $spotlight = [
          'collection' => $collection
        ];
        $document = NULL;
        if ($page->field_document_spotlight_bool->value == true){
          if ($page->field_file){
            foreach ($page->field_file as $value){
              $file = File::load($value->target_id);
              $document = entity_view($file, 'spotlight');
            }
          }
        }
      }

      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_home_page',
        '#about' => $about,
        '#resources' => $resources,
        '#courses' => $courses,
        '#speakers' => $speakers,
        '#resources_list' => $resources_list,
        '#spotlight' => $spotlight,
        '#file' => $document
      ];
      return $build;
    }
  }
