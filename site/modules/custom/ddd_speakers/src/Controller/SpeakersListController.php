<?php /**
       * @file
       * Contains \Drupal\ddd_speakers\Controller\SpeakersListController class.
       */
  namespace Drupal\ddd_speakers\Controller;
  use Drupal\Core\Controller\ControllerBase;
  /**
   * Returns responses for My Module module.
   */
  class SpeakersListController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function speakersPage() {

      if (!$page = entity_load('speakers', 1)){
        $title = 'Trouver un intervenant';
        $teaser = '';
        $data = array(
          'field_label' => $title,
          'id' => 1
        );
        $page = entity_create('speakers', $data);
        $page->save();
      }
      else {
        $title = $page->field_label->value;;
        $teaser = $page->field_teaser->value;
      }

      // getting filter form
      $filter = \Drupal::formBuilder()->getForm('Drupal\ddd_speakers\Form\FilterSpeakersForm');

      // Results from filter
      $page = pager_find_page();
      $num_per_page = 10;
      $offset = $num_per_page * $page;
      $rows = [];

      $q = \Drupal::request()->query->all();

      $query = \Drupal::entityQuery('node');
      $query->condition('type', 'speaker');
      $query->condition('status', true);
      if (!empty($q['region'])){
        $query->condition('field_region', $q['region']);
      }
      if (!empty($q['type'])){
        $query->condition('field_speaker_type', $q['type']);
      }
      if (!empty($q['specialite'])){
        $query->condition('field_speciality', $q['specialite']);
      }
      if (!empty($q['search'])){
        $condition_or = $query->orConditionGroup();
        $condition_or->condition('title', '%'.$q['search'].'%', 'LIKE');
        $condition_or->condition('field_description', '%'.$q['search'].'%', 'LIKE');
        $query->condition($condition_or);
      }
      $node_ids = $query->execute();
      $chunked_ids = array_chunk($node_ids , $num_per_page, TRUE );
      if (!empty($chunked_ids[$page]))
        $page_ids = $chunked_ids[$page];

      if (!empty($page_ids)){
        foreach ($page_ids as $id => $value) {
          if($id){
            $speaker =  \Drupal\node\Entity\Node::load($value);
            if($speaker){
              $rows[$id] = node_view($speaker, 'teaser');
            }
          }
        }
      }

      if(!empty($node_ids)) {
        pager_default_initialize(count($node_ids), $num_per_page);
      }

      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_speakers_page',
        '#results_count' => count($node_ids),
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
