<?php /**
       * @file
       * Contains \Drupal\ddd_resources\Controller\ResourcesListController class.
       */
  namespace Drupal\ddd_resources\Controller;
  use Drupal\Core\Controller\ControllerBase;
  /**
   * Returns responses for My Module module.
   */
  class ResourcesListController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function resourcesPage() {

      if (!$page = entity_load('resources_list', 'page')){
        $title = 'Centre de ressources';
        $teaser = '';
        $data = array(
          'label' => $title,
          'id' => 'page'
        );
        $page = entity_create('resources_list', $data);
        $page->save();
      }
      else {
        $title = $page->label;
        $teaser = $page->getTeaser();
      }

      // getting filter form
      $filter = \Drupal::formBuilder()->getForm('Drupal\ddd_resources\Form\FilterResourcesForm');

      // Results from filter
      $page = pager_find_page();
      $num_per_page = 1;
      $offset = $num_per_page * $page;
      $rows = [];



      $query = \Drupal::entityQuery('node');
      $query->condition('type', 'resource');
      $node_ids = $query->execute();

      if (!empty($node_ids)){
        foreach ($node_ids as $id => $value) {
          $resource =  \Drupal\node\Entity\Node::load($id);
          $rows[$id] = node_view($resource, 'teaser');
        }
      }

      if(!empty($node_ids)) {
        pager_default_initialize(count($node_ids), $num_per_page);
      }

      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_resources_page',
        '#title' => $title,
        '#teaser' => $teaser,
        '#filter' => $filter,
        '#collection' => $rows,
        '#pager'  => [
          '#type' => 'pager',
        ]
      ];
      return $build;
    }
  }
