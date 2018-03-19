<?php /**
       * @file
       * Contains \Drupal\ddd_medias\Controller\MediasController class.
       */
  namespace Drupal\ddd_medias\Controller;
  use Drupal\Core\Controller\ControllerBase;
  use Drupal\views\Views;

  /**
   * Returns responses for My Module module.
   */
  class MediasController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function mediasPage() {

      if (!$page = entity_load('medias', 1)){
        $title = 'Se former en ligne';
        $teaser = '';
        $data = array(
          'field_label' => $title,
          'id' => 1
        );
        $page = entity_create('medias', $data);
        $page->save();
      }
      else {
        $title = $page->field_label->value;
        $teaser = $page->field_teaser->value;
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
      }

      $q = \Drupal::request()->query->all();

      $view = Views::getView('formations');
      $view->setDisplay('page_1');
      $view->preExecute();
      $view->execute();
      $result = $view->result;

      if (!empty($result)){
        $i = 0;
        foreach ($result as $value){
          $media =  \Drupal\node\Entity\Node::load($value->nid);
          if ($i == 0){
            $rows[] = node_view($media, 'teaser');
            $first = false;
          }
          elseif ($i < 3){
            $rows[] = node_view($media, 'small_teaser');
          }
          elseif (!empty($q['result']) && $q['result'] == 'all'){
            $rows[] = node_view($media, 'small_teaser');
          }
          else {
            break;
          }
          $i++;
        }
      }

      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_medias_page',
        '#title' => $title,
        '#teaser' => $teaser,
        '#collection' => $rows,
        '#spotlight' => $spotlight,
      ];
      return $build;
    }
  }
