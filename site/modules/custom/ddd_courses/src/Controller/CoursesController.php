<?php
/**
 * @file
 * Contains \Drupal\ddd_courses\Controller\CoursesListController class.
 */
namespace Drupal\ddd_courses\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\paragraphs\Entity\Paragraph;
use \Drupal\Core\Url;

/**
 * Returns responses for My Module module.
 */
class CoursesController extends ControllerBase {
  /**
   * Returns markup for our custom page.
   */
  public function coursesPage() {

    if (!$page = entity_load('courses', 1)){
      $title = 'Parcours Pédagogique';
      $teaser = '';
      $footer_description = '';
      $footer_title = '';
      $footer_keys = [];
      $data = array(
        'field_label' => $title,
        'id' => 1
      );
      $page = entity_create('courses', $data);
      $page->save();
    }
    else {
      $title = $page->field_label->value;
      $teaser = $page->field_teaser->value;
      $footer_description = $page->field_description->value;
      $footer_title = $page->field_title->value;
      $footer_keys = [];
      $keys = $page->get('field_taxonomy_key_points');
      $count = $keys->count();
      for ($i = 0; $i < $count; $i++){
        $tid = $keys->get($i)->get('target_id')->getValue();
        $item = entity_load('taxonomy_term', $tid);
        $footer_keys[] = $item->get('name')->getValue()[0]['value'];
      }
    }



    $query = \Drupal::entityQuery('course');
    $ids = $query->execute();

    if (!empty($ids)){
      $view_builder = \Drupal::entityManager()->getViewBuilder('course');
      foreach ($ids as $id => $value) {
        $course =  \Drupal\ddd_courses\Entity\Course::load($id);
        $rows[] = $view_builder->view($course);
      }
    }
    $build = [
      '#type' => 'content',
      '#theme' => 'ddd_courses_page',
      '#title' => $title,
      '#teaser' => $teaser,
      '#collection' => $rows,
      '#footer_description' => $footer_description,
      '#footer_title' => $footer_title,
      '#footer_keys' => $footer_keys
    ];
    return $build;
  }

  public function parcoursPage() {
    $course_id = \Drupal::request()->get('course');
    $course =  \Drupal\ddd_courses\Entity\Course::load($course_id);
    $title = $course->label();
    $view_builder = \Drupal::entityManager()->getViewBuilder('course');
    $view_course = $view_builder->view($course);

    $episodes_ids = $course->get('field_episodes')->getValue();
    $view_builder = \Drupal::entityManager()->getViewBuilder('paragraph');
    $view_episodes = [];
    $image_left = '';
    $image_right = '';
    foreach ($episodes_ids as $value){
      $episode = Paragraph::load($value['target_id']);
      $view_episodes[] = $view_builder->view($episode);
      if (empty($image_left) && !empty($field_image_left = $episode->get('field_image_left'))){
        $image_left = $view_builder->viewField($field_image_left);
      }
       if (empty($image_right) && !empty($field_image_right = $episode->get('field_image_right'))){
        $image_right = $view_builder->viewField($field_image_right);
      }
    }
    $build = [
      '#type' => 'content',
      '#theme' => 'ddd_parcours_page',
      '#title' => $title,
      '#course' => $view_course,
      '#episodes' => $view_episodes,
      '#image_left' => $image_left,
      '#image_right' => $image_right,
    ];
    return $build;
  }

  public function episodePage() {
    $course_id = \Drupal::request()->get('course');
    $course =  \Drupal\ddd_courses\Entity\Course::load($course_id);
    $title = $course->label();
    $view_builder = \Drupal::entityManager()->getViewBuilder('course');
    $view_course = $view_builder->view($course);

    $episodes_ids = $course->get('field_episodes')->getValue();
    $view_builder = \Drupal::entityManager()->getViewBuilder('paragraph');
    $image_left = '';
    $image_right = '';
    $view_episodes = [];
    $episode_number = \Drupal::request()->get('episode');
    $episode_id = !empty($episodes_ids[$episode_number - 1]) ? $episodes_ids[$episode_number - 1] : '';
    foreach ($episodes_ids as $key => $value){
      $episode = Paragraph::load($value['target_id']);
      $view_episodes[] = $view_builder->view($episode);

      if ($key == $episode_number - 1 || ($episode_number > count($episodes_ids) && $key === 0)){
        if (empty($image_left) && !empty($field_image_left = $episode->get('field_image_left'))){
          $image_left = $view_builder->viewField($field_image_left);
        }
        if (empty($image_right) && !empty($field_image_right = $episode->get('field_image_right'))){
          $image_right = $view_builder->viewField($field_image_right);
        }
        if ($value = $episode->get('field_title')){
          $episode_title = $view_builder->viewField($value);
        }
        if ($value = $episode->get('field_keep_in_mind'))
          $episode_keep_in_mind = $view_builder->viewField($value);
        if ($value = $episode->get('field_glossary_refs'))
          $episode_glossary = $view_builder->viewField($value);
        if ($value = $episode->get('field_ressource_refs'))
          $episode_ressources = $view_builder->viewField($value);
        $episode_array = $episode->toArray();
        $episode_video = (!empty($episode_array['field_video_embed']) ? $episode_array['field_video_embed'][0]['value'] : '');
      }
      elseif ($key == $episode_number){
        $next = Url::fromRoute('ddd_courses.episode', ['course' => $course_id, 'episode' => $key + 1]);
      }
    }
    $build = [
      '#type' => 'content',
      '#theme' => 'ddd_episode_page',
      '#title' => render($episode_title) . ' - ' . $title,
      '#episode_title' => $episode_title,
      '#episode_keep_in_mind' => $episode_keep_in_mind,
      '#episode_glossary' => $episode_glossary,
      '#episode_ressources' => $episode_ressources,
      '#episode_video' => $episode_video,
      '#quizz_url' => Url::fromRoute('ddd_courses.quizz', ['course' => $course_id, 'episode' => $episode_number])->setAbsolute(true)->toString(),
      '#course' => $view_course,
      '#episodes' => $view_episodes,
      '#image_left' => $image_left,
      '#image_right' => $image_right,
      '#next' => !empty($next) ? $next : ''
    ];
    return $build;
  }

  public function quizzPage() {
    $course_id = \Drupal::request()->get('course');
    $course =  \Drupal\ddd_courses\Entity\Course::load($course_id);
    $title = $course->label();
    $view_builder = \Drupal::entityManager()->getViewBuilder('course');
    $view_course = $view_builder->view($course);

    $episodes_ids = $course->get('field_episodes')->getValue();
    $view_builder = \Drupal::entityManager()->getViewBuilder('paragraph');
    $image_left = '';
    $image_right = '';
    $view_episodes = [];
    $episode_number = \Drupal::request()->get('episode');
    $episode_id = !empty($episodes_ids[$episode_number - 1]) ? $episodes_ids[$episode_number - 1] : '';
    $quizz = [];
    foreach ($episodes_ids as $key => $value){
      $episode = Paragraph::load($value['target_id']);
      $view_episodes[] = $view_builder->view($episode);

      if ($key == $episode_number - 1 || ($episode_number > count($episodes_ids) && $key === 0)){
        if (empty($image_left) && !empty($field_image_left = $episode->get('field_image_left'))){
          $image_left = $view_builder->viewField($field_image_left);
        }
         if (empty($image_right) && !empty($field_image_right = $episode->get('field_image_right'))){
          $image_right = $view_builder->viewField($field_image_right);
        }
        if ($value = $episode->get('field_title'))
          $episode_title = $view_builder->viewField($value);
        if ($value = $episode->get('field_keep_in_mind'))
          $episode_keep_in_mind = $view_builder->viewField($value);
        if ($value = $episode->get('field_glossary_refs'))
          $episode_glossary = $view_builder->viewField($value);
        if ($value = $episode->get('field_ressource_refs'))
          $episode_ressources = $view_builder->viewField($value);
        $episode_array = $episode->toArray();
        $episode_video = (!empty($episode_array['field_video_embed']) ? $episode_array['field_video_embed'][0]['value'] : '');

        $quizz_ids = $episode->get('field_quizz')->getValue();

        $quizz = array_map(
          function($value){
            $question = Paragraph::load($value['target_id']);
            $right_answer = $question->get('field_right_answer')->getValue() ? $question->get('field_right_answer')->getValue()[0]['value'] : '';
            return [
              'title' => $question->get('field_title')->getValue()? $question->get('field_title')->getValue()[0]['value'] : '',
              'subtitle' => $question->get('field_subtitle')->getValue() ? $question->get('field_subtitle')->getValue()[0]['value'] : '',
              'answer' => $right_answer,
              'answer_description' => $question->get('field_answer_description')->getValue() ? $question->get('field_answer_description')->getValue()[0]['value'] : '',
              'answers' => array_map(
                function ($answer, $key) use ($right_answer) {
                  $answer_keys = [
                    'A' => 0,
                    'B' => 1,
                    'C' => 2,
                  ];
                  return [
                    'title' => $answer['value'],
                    'frame' => $key == $answer_keys[$right_answer] ? 1 : 2
                  ];
                },
                $question->get('field_answers')->getValue(),
                array_keys($question->get('field_answers')->getValue())
              )
            ];
          },
          $episode->get('field_quizz')->getValue()
        );
      }
      elseif ($key == $episode_number){
        $next = Url::fromRoute('ddd_courses.episode', ['course' => $course_id, 'episode' => $key + 1]);
      }
    }
    $build = [
      '#type' => 'content',
      '#theme' => 'ddd_quizz_page',
      '#title' => $title,
      '#episode_title' => $episode_title,
      '#episode_keep_in_mind' => $episode_keep_in_mind,
      '#episode_glossary' => $episode_glossary,
      '#episode_ressources' => $episode_ressources,
      '#episode_video' => $episode_video,
      '#course' => $view_course,
      '#episodes' => $view_episodes,
      '#image_left' => $image_left,
      '#image_right' => $image_right,
      '#quizz' => $quizz,
      '#next' => !empty($next) ? $next : ''
    ];
    return $build;
  }

}
