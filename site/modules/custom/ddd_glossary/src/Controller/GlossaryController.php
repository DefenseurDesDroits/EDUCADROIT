<?php /**
       * @file
       * Contains \Drupal\ddd_glossary\Controller\GlossaryController class.
       */
  namespace Drupal\ddd_glossary\Controller;
  use Drupal\Core\Controller\ControllerBase;
  use Drupal\paragraphs\Entity\Paragraph;
  use Drupal\ddd_glossary\Entity\Vocabulary;
  use Drupal\ddd_glossary\Entity\Glossary;
  /**
   * Returns responses for My Module module.
   */
  class GlossaryController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function glossaryPage() {

      if (!$page = Glossary::load(1)){
        $page = Glossary::create([
          'field_label' => 'Glossaire',
          'field_teaser' => '',
          'id' => 1
        ]);
        $page->save();
      }
      $collection = [];
      array_walk(
        array_column($page->get('field_vocabularies')->getValue(), 'target_id'),
        function ($value) use (&$collection){
          $utf8 = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'];
          $item = Vocabulary::load($value);
          $collection[strtoupper(strtr($item->label(), $utf8))] = \Drupal::entityTypeManager()->getViewBuilder('vocabulary')->view($item, 'default');
        }
      );
      ksort($collection);
      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_glossary_page',
        '#title' => $page->field_label->value,
        '#teaser' => $page->field_teaser->value,
        '#collection' => $collection
      ];
      return $build;
    }
  }
