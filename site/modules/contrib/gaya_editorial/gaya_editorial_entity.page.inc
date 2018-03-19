<?php

/**
 * @file
 * Contains gaya_editorial_entity.page.inc..
 *
 * Page callback for Gaya editorial entity entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Gaya editorial entity templates.
 *
 * Default template: gaya_editorial_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_gaya_editorial_entity(array &$variables) {
  // Fetch GayaEditorialEntity Entity Object.
  $gaya_editorial_entity = $variables['elements']['#gaya_editorial_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

/**
* Prepares variables for a custom entity type creation list templates.
*
* Default template: gaya_editorial_entity-content-add-list.html.twig.
*
* @param array $variables
*   An associative array containing:
*   - content: An array of gaya_editorial_entity-types.
*
* @see block_content_add_page()
*/
function template_preprocess_gaya_editorial_entity_content_add_list(&$variables) {
  $variables['types'] = array();
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = array(
      'link' => Link::fromTextAndUrl($type->label(), new Url('entity.gaya_editorial_entity.add_form', array(
        'gaya_editorial_entity_type' => $type->id()
      ), array('query' => $query))),
      'description' => array(
      '#markup' => $type->label(),
      ),
      'title' => $type->label(),
      'localized_options' => array(
      'query' => $query,
      ),
    );
  }
}
