<?php
/**
 * @file
 * Contains \Drupal\gaya_editorial_slideshow\Plugin\Field\FieldWidget\ImagesSlideshowConfigurationDefaultWidget.
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 */

namespace Drupal\gaya_editorial_slideshow\Plugin\Field\FieldWidget;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Image;


/**
 * Plugin implementation of the 'ge_imagesslideshow_default' widget.
 *
 * @FieldWidget(
 *   id = "ge_imagesslideshow_configuration_widget_default",
 *   module = "gaya_editorial_slideshow",
 *   label = @Translation("GE Slideshow Configuration default"),
 *   field_types = {
 *     "ge_imagesslideshow_configuration"
 *   }
 * )
 */
class ImagesSlideshowConfigurationDefaultWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;


  /**
   * Config manager service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructs a InlineEntityFormBase object.
   *
   * @param array $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityTypeManager $entity_type_manager, ModuleHandlerInterface $module_handler, ConfigFactory $config_factory) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings, $entity_type_manager);
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['markup'] = array(
      '#markup' => '<strong>' . $this->t('Configuration of the slideshow') . '</strong><br/>' . $this->t('This configuration is only valid for the current slideshow. You can add more 1 slideshow by content with a different slideshow configuration.'),
    );
    $element['style_name'] = array(
      '#title' => $this->t('Select the style name of the large picture'),
      '#type' => 'select',
      '#options' => $this->formElementGetStyledNameList(),
      '#required' => TRUE,
      '#default_value' => isset($items[$delta]->style_name) ? $items[$delta]->style_name : NULL,
    );
    $element['theme_style'] = array(
      '#title' => $this->t('Which version you want for the slideshow?'),
      '#type' => 'select',
      '#options' => $this->formElementGetThemeStyleList(),
      '#required' => TRUE,
      '#default_value' => isset($items[$delta]->theme_style) ? $items[$delta]->theme_style : NULL,
    );
    $element['little_picture'] = array(
      '#title' => $this->t('Do you want show the little picture list?'),
      '#type' => 'select',
      '#options' => array('0' => $this->t('No'), '1' => $this->t('Yes')),
      '#required' => TRUE,
      '#default_value' => isset($items[$delta]->little_picture) ? $items[$delta]->little_picture : 0,
    );
    $element['style_name_little'] = array(
      '#title' => $this->t('Select the style name of the little picture'),
      '#type' => 'select',
      '#options' => $this->formElementGetStyledNameList(),
      '#required' => TRUE,
      '#default_value' => isset($items[$delta]->style_name_little) ? $items[$delta]->style_name_little : NULL,
    );
    $element['autostart'] = array(
      '#title' => $this->t('Do you want autostart the slideshow?'),
      '#type' => 'select',
      '#options' => array('0' => $this->t('No'), '1' => $this->t('Yes')),
      '#required' => TRUE,
      '#default_value' => isset($items[$delta]->autostart) ? $items[$delta]->autostart : 0,
    );
    return $element;
  }

  /**
   * Retreive all styles Images List from the Storage image_style of the container entity_type.manager
   *
   * @return array of images styles
   */
  private function formElementGetStyledNameList() {
    $images_styles_load = $this->entityTypeManager->getStorage('image_style')->loadMultiple();
    $images_style = array();
    foreach ($images_styles_load as $image_style) {
      $images_style[$image_style->id()] = $image_style->label();
    }
    return $images_style;
  }

  /**
   * Retrieve the list of the theme slideshow
   * - It can be getted from others modules implements the hook_ge_slideshow_theme (settings)
   *
   * @return array
   */
  private function formElementGetThemeStyleList() {
    $config = $this->configFactory->get('gaya_editorial_slideshow.settings');
    $hook = $config->get('hook_ge_slideshow_theme');
    $modules = array();
    $modules_founds = $this->moduleHandler->getImplementations($hook);
    foreach ($modules_founds as $module) {
      $module_information = $this->moduleHandler->invoke($module, $hook);
      foreach ($module_information as $theme => $information) {
        $modules[$theme] = $information['name'] . ' ' . $information['version'];
      }
    }
    return $modules;
  }

}

