<?php

namespace Drupal\ddd_base\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Class ParcoursService.
 *
 * @package Drupal\ddd_base
 */
class ParcoursServiceThemeNegociator implements ThemeNegotiatorInterface {
  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $route_name = $route_match->getRouteName();
    if ($route_name == 'ddd_courses.parcours' || $route_name == 'ddd_courses.episode' || $route_name == 'ddd_courses.quizz') {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return 'ddd_parcours';
  }

}
