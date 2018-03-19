<?php
/**
 * @file
 * Contains \Drupal\ddd_base\Controller\HomeController class.
 */
  namespace Drupal\ddd_base\Controller;
  use Drupal\Core\Controller\ControllerBase;
  use Drupal\image\Entity\ImageStyle;
  use Drupal\Core\Url;
  use Drupal\Core\Entity;
  use Drupal\file\Entity\File;
  /**
   * Returns responses for My Module module.
   */
  class UserController extends ControllerBase {

    /**
     * Returns markup for our custom page.
     */
    public function profilePage() {
      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_user_page',
        '#title' => t('Mon profil'),
        '#teaser' => t('Pour activer votre compte et avoir accès au forum, vous devez renseigner les informations ci-dessous.'),
        '#form' => \Drupal::formBuilder()->getForm('Drupal\ddd_base\Form\ProfilePageForm')
      ];
      return $build;
    }

    public function firstConnectionPage() {
      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_user_page',
        '#title' => t('Première connexion'),
        '#form' => \Drupal::formBuilder()->getForm('Drupal\ddd_base\Form\FirstConnectionForm')
      ];
      return $build;
    }

    public function ForgottenPasswordPage() {
      $build = [
        '#type' => 'content',
        '#theme' => 'ddd_user_page',
        '#title' => t('Mot de passe oublié'),
        '#form' => \Drupal::formBuilder()->getForm('Drupal\ddd_base\Form\RetrievePasswordForm')
      ];
      return $build;
    }
  }
