<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Plugin\Block\HeaderNav.
 */
namespace Drupal\ddd_base\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * @Block(
 *   id = "HeaderNav",
 *   admin_label = @Translation("HeaderNav"),
 *   category = @Translation("Custom") *)
 */
class HeaderNav extends BlockBase {
  public function build() {
    $menu_tree = \Drupal::menuTree();
    $menu_name = 'main';

    // Build the typical default set of menu tree parameters.
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);

    // Load the tree based on this set of parameters.
    $tree = $menu_tree->load($menu_name, $parameters);

    // Transform the tree using the manipulators you want.
    $manipulators = array(
      // Only show links that are accessible for the current user.
      array('callable' => 'menu.default_tree_manipulators:checkAccess'),
      // Use the default sorting of menu links.
      array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
    );
    $tree = $menu_tree->transform($tree, $manipulators);

    // Finally, build a renderable array from the transformed tree.
    $menu = $menu_tree->build($tree);

    $menu_html = drupal_render($menu);

    return [
      '#markup' => $menu_html,
      '#cache' => [
        'contexts' => [
          'url.path',
        ],
      ],
    ];
  }
}
