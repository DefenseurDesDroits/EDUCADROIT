<?php
/**
 * @file
 * Contains \Drupal\gaya_editorial_slideshow\Plugin\Field\FieldFormatter\ImagesSlideshowConfigurationDefaultFormatter.
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 */

namespace Drupal\gaya_editorial_slideshow\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ge_imagesslideshow_default' formatter.
 *
 * @FieldFormatter(
 *   id = "ge_imagesslideshow_configuration_formatter_default",
 *   module = "gaya_editorial_slideshow",
 *   label = @Translation("GE Slideshow Configurationdefault"),
 *   field_types = {
 *     "ge_imagesslideshow_configuration"
 *   }
 * )
 */
class ImagesSlideshowConfigurationDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();
    foreach ($items as $delta => $item) {
      $source = array(
        '#style_name' => $item->style_name,
        '#theme_style' => $item->theme_style,
        '#little_picture' => $item->little_picture,
        '#style_name_little' => $item->style_name_little,
        '#autostart' => $item->autostart,
      );
      $elements[$delta] = $source;
    }
    return $elements;
  }

}