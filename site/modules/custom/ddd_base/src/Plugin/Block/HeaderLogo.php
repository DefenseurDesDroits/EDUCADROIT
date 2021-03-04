<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Plugin\Block\HeaderLogo.
 */

namespace Drupal\ddd_base\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\image\Entity\ImageStyle;
use Drupal\file\Entity\File;

/**
 * @Block(
 *   id = "HeaderLogo",
 *   admin_label = @Translation("HeaderLogo"),
 *   category = @Translation("Custom") *)
 */
class HeaderLogo extends BlockBase
{
  public function build()
  {
    $config = _ddd_base_get_configs();
    $header = $config->getHeader();
    if ($logos = $header['ddd_logo']) {
      foreach ($logos as $key => $value) {
        $file = File::load($value);
      }
      if ($file) {
        $uri = $file->getFileUri();
        $image_url = ImageStyle::load('194x80')->buildUrl($uri);
        $header['ddd_logo'] = $image_url;
      }
    } else {
      $header['ddd_logo'] = [];
    }
    return [
      '#markup' => 'Logo',
      'config' => $header
    ];
  }

}
