<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Plugin\Block\FooterBottom.
 */
namespace Drupal\ddd_base\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use  \Drupal\image\Entity\ImageStyle;
use Drupal\file\Entity\File;
/**
 * @Block(
 *   id = "FooterBottom",
 *   admin_label = @Translation("FooterBottom"),
 *   category = @Translation("Custom") *)
 */
class FooterBottom extends BlockBase {
  public function build() {
    $config = _ddd_base_get_configs();
    $footer = $config->getFooter();
    $footer_images = $footer['ddd_logo'];
    foreach ($footer_images as $key => $value) {
      $file = File::load($value);
    }
    $uri = $file->getFileUri();
    $image_url = ImageStyle::load('218x92')->buildUrl($uri);
    $footer['ddd_logo'] = $image_url;
    return [
      '#markup' => 'FooterBottom',
      'config' => $footer
    ];
  }

}
