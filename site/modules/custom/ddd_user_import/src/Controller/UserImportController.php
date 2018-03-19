<?php
/**
 * @file
 * contains \Drupal\ddd_user_import\Controller\UserImportController.
 */

namespace Drupal\ddd_user_import\Controller;

use \Drupal\user\Entity\User;
use \Drupal\file\Entity\File;
use \Drupal\Core\Entity\EntityStorageException;

class UserImportController {

  public static function importPage() {
    $form = \Drupal::formBuilder()->getForm('Drupal\ddd_user_import\Form\UserImportForm');
    return $form;
  }

  /**
   * Processes an uploaded CSV file, creating a new user for each row of values.
   *
   * @param \Drupal\file\Entity\File $file
   *   The File entity to process.
   *
   * @param array $config
   *   An array of configuration containing:
   *   - roles: an array of role ids to assign to the user
   *
   * @return array
   *   An associative array of values from the filename keyed by new uid.
   */
  public static function processUpload(File $file, array $config) {
    $handle = fopen($file->destination, 'r');
    $created = [];
    while ($row = fgetcsv($handle)) {
      if ($values = self::prepareRow($row, $config)) {
        if ($uid = self::createUser($values)) {
          $created[$uid] = $values;
        }
      }
    }

    return $created;
  }

  /**
   * Prepares a new user from an upload row and current config.
   *
   * @param $row
   *   A row from the currently uploaded file.
   *
   * @param $config
   *   An array of configuration containing:
   *   - roles: an array of role ids to assign to the user
   *
   * @return array
   *   New user values suitable for User::create().
   */
  public static function prepareRow(array $row, array $config) {
    $preferred_username = (strtolower($row[0]));
    $i = 0;
    while (self::usernameExists($i ? $preferred_username . $i : $preferred_username)) {
      $i++;
    }
    $username = $i ? $preferred_username . $i : $preferred_username;
    return [
      'uid' => NULL,
      'name' => $preferred_username,
      'field_firstname' => $row[1],
      'field_surname' => $row[2],
      'field_organization' => $row[3],
      'field_capacities' => $row[4],
      'pass' => NULL,
      'mail' => $row[0],
      'status' => 1,
      'created' => REQUEST_TIME,
      'roles' => array_values($config['roles']),
    ];
  }

  /**
   * Returns user whose name matches $username.
   *
   * @param string $username
   *   Username to check.
   *
   * @return array
   *   Users whose names match username.
   */
  private static function usernameExists($username) {
    return \Drupal::entityQuery('user')->condition('name', $username)->execute();
  }

  /**
   * Creates a new user from prepared values.
   *
   * @param $values
   *   Values prepared from prepareRow().
   *
   * @return \Drupal\user\Entity\User
   *
   */
  private function createUser($values) {
    $user = User::create($values);
    try {
      if ($user->save()) {
        return $user->id();
      }
    }
    catch (EntityStorageException $e) {
      drupal_set_message(t('Could not create user %fname %lname (username: %uname) (email: %email); exception: %e', [
        '%e' => $e->getMessage(),
        '%fname' => $values['field_firstname'],
        '%lname' => $values['field_surname'],
        '%uname' => $values['name'],
        '%email' => $values['mail'],
      ]), 'error');
    }
    return FALSE;
  }

}
