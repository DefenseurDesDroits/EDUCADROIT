<?php /**
       * @file
       * Contains \Drupal\ddd_medias\Controller\MediasListController class.
       */
  namespace Drupal\ddd_medias\Controller;
  use Drupal\Core\Controller\ControllerBase;
  /**
   * Returns responses for My Module module.
   */
  class MediasListController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function mediasPage() {

      if (!$page = entity_load('medias_list', 'page')){
        $title = 'Se former en ligne';
        $teaser = '';
        $data = array(
          'label' => $title,
          'id' => 'page'
        );
        $page = entity_create('medias_list', $data);
        $page->save();
      }
      else {
        $title = $page->label;
        $teaser = $page->getTeaser();
      }

      // Results from filter
      $page = pager_find_page();
      $num_per_page = 1;
      $offset = $num_per_page * $page;
      $rows = [];



      $query = \Drupal::entityQuery('node');
      $query->condition('type', 'media');
      $node_ids = $query->execute();

      if (!empty($node_ids)){
        $first = true;
        foreach ($node_ids as $id => $value) {
          $media =  \Drupal\node\Entity\Node::load($id);
          if ($first == true){
            $rows[$id] = node_view($media, 'teaser');
            $first = false;
          }
          else{
            $rows[$id] = node_view($media, 'small_teaser');
          }
        }
      }

      if(!empty($node_ids)) {
        pager_default_initialize(count($node_ids), $num_per_page);
      }

      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_medias_page',
        '#title' => $title,
        '#teaser' => $teaser,
        '#collection' => $rows,
        '#pager'  => [
          '#type' => 'pager',
        ]
      ];
      return $build;
    }
  }
