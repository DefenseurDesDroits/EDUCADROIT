<?php /**
       * @file
       * Contains \Drupal\ddd_forum\Controller\ForumController class.
       */
  namespace Drupal\ddd_forum\Controller;
  use Drupal\Core\Controller\ControllerBase;
  use Symfony\Component\HttpFoundation\RedirectResponse;
  /**
   * Returns responses for My Module module.
   */
  class ForumController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function connectionPage() {
      if (\Drupal::currentUser()->isAnonymous()) {
        // getting filter form
        $form = \Drupal::formBuilder()->getForm('Drupal\ddd_forum\Form\ForumConnectionForm');

        $build = [
          '#type' => 'content',
          '#theme' => 'ddd_forum_connection_page',
          '#form' => $form,
        ];
        return $build;
      }
      else{
        return new RedirectResponse(\Drupal::url('forum.index'));
      }

    }

    public function collectionPage() {

      // $forum = Drupal\forum::ForumIndexStorageInterface('forums');


      $terms = \Drupal\taxonomy\Entity\Vocabulary::load('forums');

      var_dump($terms);

      // $query = \Drupal::entityQuery('forum');
      // $ids = $query->execute();

      // if (!empty($ids)){
      //   $view_builder = \Drupal::entityManager()->getViewBuilder('forum');
      //   foreach ($ids as $id => $value) {
      //     // $course =  Forum::load($id);
      //     // $rows[] = $view_builder->view($course);
      //   }
      // }
        // getting filter form
      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_forum_page',
        // '#collection' => $collection,
      ];
      return $build;
    }
  }
