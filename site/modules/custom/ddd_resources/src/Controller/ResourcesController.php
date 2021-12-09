<?php /**
 * @file
 * Contains \Drupal\ddd_resources\Controller\ResourcesController class.
 */

namespace Drupal\ddd_resources\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for My Module module.
 */
class ResourcesController extends ControllerBase
{

  /**
   * Returns markup for our custom page.
   */
  public function resourcesPage()
  {

    if (!$page = entity_load('resources', 1)) {
      $title = 'Centre de ressources';
      $teaser = '';
      $data = array(
        'label' => $title,
        'id' => 1
      );
      $page = entity_create('resources', $data);
      $page->save();
    } else {
      $title = $page->field_label->value;
      $teaser = $page->field_teaser->value;
      $collection = [];
      if (!empty($page->field_spotlights)) {
        foreach ($page->field_spotlights as $value) {
          $entity = entity_load('paragraph', $value->target_id);
          $collection[] = entity_view($entity, 'full');
        }
      }
      $spotlight = [
        'collection' => $collection
      ];
    }

    // getting filter form
    $filter = \Drupal::formBuilder()->getForm('Drupal\ddd_resources\Form\FilterResourcesForm', 'Filtrer');

    // Results from filter
    $page = pager_find_page();
    $num_per_page = 10;
    $offset = $num_per_page * $page;
    $rows = [];


    $q = \Drupal::request()->query->all();

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'resource');
    $query->condition('field_status', 'accepted');
    $query->exists('field_paragraphs');
    if (!empty($q['point_cle'])) {
      $query->condition('field_key_points', $q['point_cle']);
    }
    if (!empty($q['theme'])) {
      $query->condition('field_themes', $q['theme']);
    }
    if (!empty($q['public'])) {
      $query->condition('field_publics', $q['public']);
    }
    if (!empty($q['format'])) {
      $query->condition('field_format', $q['format']);
    }
    $node_ids = $query->execute();

    $chunked_ids = array_chunk($node_ids, $num_per_page, TRUE);
    if (!empty($chunked_ids[$page]))
      $page_ids = $chunked_ids[$page];

    if (!empty($page_ids)) {
      foreach ($page_ids as $id => $value) {
        $resource = \Drupal\node\Entity\Node::load($id);
        $rows[$id] = node_view($resource, 'teaser');
      }
    }

    if (!empty($node_ids)) {
      pager_default_initialize(count($node_ids), $num_per_page);
    }

    $build = [
      '#type' => 'content',
      '#theme' => 'ddd_resources_page',
      '#results_count' => count($node_ids),
      '#title' => $title,
      '#teaser' => $teaser,
      '#filter' => $filter,
      '#collection' => $rows,
      '#spotlight' => $spotlight,
      '#pager' => [
        '#type' => 'pager',
      ],
      '#share' => [
        '#theme' => 'share'
      ]
    ];
    return $build;
  }

  /**
   * Returns markup for our custom page.
   */
  public function offlinePage()
  {
    $response = [];
    $q = \Drupal::request()->query->all();

    if (!empty($q['id'])) {
      $resource = \Drupal\node\Entity\Node::load($q['id']);
      $options = ['absolute' => TRUE];
      $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $q['id']], $options);
      // set params
      $params = [
        'title' => $resource->getTitle(),
        'link' => $url->toString()
      ];

      // mail sending
      $module = 'ddd_base';
      $key = 'offline_resource';
      $from = $this->config('system.site')->get('mail');
      if ($page = entity_load('resources', 1)) {
        $to = $page->field_email->value;
      }
      if (empty($to)) {
        $to = $this->config('system.site')->get('mail');
      }
      $language_code = 'fr';
      $send_now = TRUE;
      $result = \Drupal::service('plugin.manager.mail')->mail($module, $key, $to, $language_code, $params, $from, $send_now);
      if ($result) {
        $data = ['message' => 'Merci pour votre aide.'];
      } else {
        $data = ['message' => 'Une erreur s\'est produite. Veuillez réessayer ulterieurement'];
      }
    } else {
      $data = ['message' => 'Une erreur s\'est produite. Veuillez réessayer ulterieurement'];
    }
    return new JsonResponse($data);

    // DEBUG
    $build = [
      '#type' => 'content',
      '#theme' => 'ddd_resources_page',
    ];
    return $build;
  }
}
