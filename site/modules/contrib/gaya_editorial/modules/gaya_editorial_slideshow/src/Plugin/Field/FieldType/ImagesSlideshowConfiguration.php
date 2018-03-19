<?php
/**
 * @file
 * Contains \Drupal\gaya_editorial_slideshow\Plugin\Field\FieldType\ImagesSlideshowConfiguration.
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 */

namespace Drupal\gaya_editorial_slideshow\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'GE ImagesSlideshow' field type.
 *
 * @FieldType(
 *   id = "ge_imagesslideshow_configuration",
 *   module = "gaya_editorial_slideshow",
 *   label = @Translation("Images Slideshow Configuration Field"),
 *   description = @Translation("This field stores the Configuration of the Images Slideshow in the database."),
 *   default_widget = "ge_imagesslideshow_configuration_widget_default",
 *   default_formatter = "ge_imagesslideshow_configuration_formatter_default"
 * )
 */
class ImagesSlideshowConfiguration extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'style_name' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
          'description' => 'The STYLE_NAME applicate of the picture list.',
        ),
        'theme_style' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
          'description' => 'The THEME applicate of the slideshow.',
        ),
        'little_picture' => array(
          'type' => 'int',
          'not null' => TRUE,
          'default' => 0,
          'description' => 'The slideshow have a little picture list.',
        ),
        'style_name_little' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
          'description' => 'The STYLE_NAME applicate of the little picture list.',
        ),
        'autostart' => array(
          'type' => 'int',
          'not null' => TRUE,
          'description' => 'The slideshow start automaticaly ?.',
          'default' => 0,
        ),
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('style_name')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['style_name'] = DataDefinition::create('string')
      ->setLabel(t('Which style picture you want for the slideshow pictures?'))
      ->setRequired(TRUE);

    $properties['theme_style'] = DataDefinition::create('string')
      ->setLabel(t('Which version you want for the slideshow?'))
      ->setRequired(TRUE);

    $properties['little_picture'] = DataDefinition::create('integer')
      ->setLabel(t('Do you want show a little picture list?'))
      ->setRequired(TRUE);

    $properties['style_name_little'] = DataDefinition::create('string')
      ->setLabel(t('Which style picture you want for the slideshow little pictures?'))
      ->setRequired(TRUE);

    $properties['autostart'] = DataDefinition::create('integer')
      ->setLabel(t('Do you want autostart the slideshow?'))
      ->setRequired(TRUE);

    return $properties;
  }

}

