<?php
/**
 * @file
 * Contains \Drupal\gaya_editorial\Form\ModuleForm.
 */

namespace Drupal\gaya_editorial\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\gaya_editorial\Controller\GayaEditorialController;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Implements an module form.
 */
class ModuleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gaya_editorial_modules_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Get all module as module hook
    $ls_modules = GayaEditorialController::listAllModulesForGayaEditorial();

    // Clean and get more information
    $ls_modules_table = NULL;
    foreach ($ls_modules as $module_key => $module_name) {
      $ls_modules_table[$module_key] = array(
        ($module_name->status == 0) ? $this->t('Disabled') : $this->t('Enabled'),
        $module_name->getName(),
        $module_name->info['description'],
        $module_name->info['version']
      );
      if ($module_name->status == 1) {
        $default_values[$module_key] = TRUE;
      }
    }

    // Set the header of the table select
    $header = array(
      $this->t('Status'),
      $this->t('Module name'),
      $this->t('Description'),
      $this->t('Version')
    );

    // Table select
    $form['modules'] = array(
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $ls_modules_table,
      '#multiple' => TRUE,
      '#default_value' => $default_values,
      '#empty' => $this->t('No module found implementing this hook.')
    );

    // Actions
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save and enable / disable modules'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    try {

      // Instance configFactory
      $config_factory = \Drupal::configFactory();

      // Get all module as module hook
      $ls_modules = GayaEditorialController::listAllModulesForGayaEditorial();

      $countDisabled = 0;
      $countEnabled = 0;
      $countNotChanged = 0;
      foreach ($form_state->getValue('modules') as $key => $value) {
        if ($value == '0') {
          if ($ls_modules[$key]->status == 0) {
            ++$countNotChanged;
          }
          else {
            $uninstallModules[] = $key;

          }
        }
        else {
          if ($ls_modules[$key]->status == 1) {
            ++$countNotChanged;
          }
          else {
            $installModule[] = $key;
          }
        }
      }

      ##########################
      # Start uninstall module #
      ##########################
      // Test if the list of module can be uninstalled directly.
      if ($reasons = \Drupal::service('module_installer')
        ->validateUninstall($uninstallModules)
      ) {
        foreach ($reasons as $reason) {
          $reason_message[] = implode(', ', $reason);
        }
        throw new Exception('The following reasons prevent the modules (' . implode(',', $uninstallModules) . ') from being uninstalled: ' . implode('; ', $reason_message));
      }

      // Uninstall the module
      \Drupal::service('module_installer')->uninstall($uninstallModules);

      // Clean the configuration
      foreach ($uninstallModules as $key) {
        // Uninstall the config
        \Drupal::service('config.manager')->uninstall('module', $key);

        // Remove the config from the current active stage !
        $dir = DRUPAL_ROOT . DIRECTORY_SEPARATOR . drupal_get_path('module', $key) . DIRECTORY_SEPARATOR . 'config/install';
        $files = file_scan_directory($dir, '/.*\.yml$/');
        foreach ($files as $file) {
          $config_factory->getEditable($file->name)->delete();
        }
        ++$countDisabled;
      }

      ########################
      # Start install module #
      ########################
      // Install the module
      if (count($installModule) > 0) {
        \Drupal::service('module_installer')->install($installModule);
        ++$countEnabled;
      }


      #####################################
      # Finish install / uninstall module #
      #####################################
      // Flush cache
      drupal_flush_all_caches();

      // Send confirmation messages
      drupal_set_message($this->t('Your configuration have been save and the cache have been cleared.'));
      drupal_set_message($this->t('<em>[Info]</em> @countDisabled modules disabled, @countEnabled modules enabled, @countNotChanged modules unchanged', array(
        '@countDisabled' => $countDisabled,
        '@countEnabled' => $countEnabled,
        '@countNotChanged' => $countNotChanged
      )));

    } catch (Exception $e) {

      // Flush cache
      drupal_flush_all_caches();

      // Set message and redirect to the current page
      drupal_set_message($e->getMessage(), 'error');
      $this->redirect('gaya_editorial.admin_config_page');
    }

  }
}