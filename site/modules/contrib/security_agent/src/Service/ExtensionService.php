<?php
namespace Drupal\security_agent\Service;

use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ThemeHandlerInterface;

class ExtensionService {

  /**
   * @var ModuleExtensionList
   */
  protected $moduleExtensionList;

  /**
   * @var ThemeHandlerInterface
   */
  protected $themeHandler;

  public function __construct(ModuleExtensionList $extension_list_module, ThemeHandlerInterface $themeHandler) {
    $this->moduleExtensionList = $extension_list_module;
    $this->themeHandler = $themeHandler;
  }

  public function listExtension() {
    $modules = [];

    $this->getInfos($this->moduleExtensionList->reset()->getList(), $modules);
    $this->getInfos($this->themeHandler->rebuildThemeData(), $modules);

    return $modules;
  }

  /**
   * @param \Drupal\Core\Extension\Extension[] $projects
   * @param array $list
   */
  protected function getInfos($projects, &$list) {
    foreach ($projects as $file) {
      if ($file->getType() == 'profile' || $file->origin == 'core' ) {
        continue;
      }
      if (empty($file->info)) {
        continue;
      }
      if (!empty($file->info['hidden']) && empty($status)) {
        continue;
      }
      if (!empty($file->info['hidden']) && isset($file->info['package']) && $file->info['package'] == 'Testing') {
        continue;
      }
      if (empty($file->info['project'])) {
        continue;
      }
      $information = $file->info;
      $project = $information['project'];
      $information['patched'] = (int) file_exists($file->getPath() . '/PATCHES.txt');

      if (!isset($list[$project])) {
        $list[$project] = [
          'title' => $information['name'],
          'version' => $information['version'],
          'patched' => $information['patched']
        ];
      }
    }
  }
}
