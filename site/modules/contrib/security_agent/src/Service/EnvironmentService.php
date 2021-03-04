<?php
namespace Drupal\security_agent\Service;

use \Drupal\Core\Database\Connection;

class EnvironmentService {

  /**
   * The module handler.
   *
   * @var Connection
   */
  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public function getEnvironment() {
    $environment = [];

    $environment[] = [
      'type' => 'backend',
      'name' => 'php',
      'version' => phpversion()
    ];
    $environment[] = [
      'type' => 'os',
      'name' => php_uname('s'),
      'version' => php_uname('r'),
      'arch' => php_uname('m')
    ];
    $environment[] = [
      'type' => 'database',
      'name' => $this->database->driver(),
      'version' => $this->database->version()
    ];

    return $environment;
  }
}
