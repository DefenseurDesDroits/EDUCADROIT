<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\Controller\GayaEditorialController.
 */

namespace Drupal\gaya_editorial\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for GayaEditorial routes.
 */
class GayaEditorialController extends ControllerBase {

  /**
   * Constructs a Controller object.
   *
   */
  public function __construct() {
  }

  /**
   * Return the admin Config Page of the GayaEditorial Module
   * - List of available module in current subPath Modules
   *
   * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
   */
  public function adminConfigPage() {
    $output = array();
    $output['title_page'] = array(
      '#markup' => '<h2>' . $this->t('Gaya Editorial Module') . '</h2>',
    );
    $output['head_page'] = array(
      '#markup' => $this->t('This module provide all content block editorial for this site. This module is contributed by Gaya and is maintened by Gaya. This module provide all fields, and templates for most type of content editorial block.'),
    );

    $output['title_page_2'] = array(
      '#markup' => '<p><hr/></p><h2>' . $this->t('List of module can be available and can be used.') . '</h2>',
    );
    $output['title_page_3'] = array(
      '#markup' => \Drupal::service('renderer')->render(\Drupal::formBuilder()
        ->getForm('Drupal\gaya_editorial\Form\ModuleForm'))
    );

    return $output;
  }

  /**
   * Return the list of module can be
   *
   * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
   */
  public function listAllModulesForGayaEditorial() {
    $modules = system_rebuild_module_data();
    uasort($modules, 'system_sort_modules_by_info_name');
    foreach ($modules as $module_key => $module_name) {
      if( $module_name->getName() != 'gaya_editorial') {
        if( !isset($module_name->info['isGayaEditorial']) || $module_name->info['isGayaEditorial'] != 'yes') {
          unset($modules[$module_key]);
        }
      } else {
        unset($modules[$module_key]);
      }
    }
    return $modules;
  }


}