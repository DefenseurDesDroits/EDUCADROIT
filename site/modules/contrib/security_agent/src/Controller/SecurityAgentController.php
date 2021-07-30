<?php
namespace Drupal\security_agent\Controller;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\security_agent\Service\AccessService;
use Drupal\security_agent\Service\EnvironmentService;
use Drupal\security_agent\Service\ExtensionService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PhpStatusController
 * @package Drupal\php_status\Controller
 */
class SecurityAgentController extends ControllerBase {

  /**
   * Request stack
   *
   * @var RequestStack
   */
  protected $request;

  /**
   * Cache Backend
   *
   * @var CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * @var JsonResponse
   */
  protected $jsonResponse;

  /**
   * @var AccessService
   */
  protected $accessService;

  /**
   * @var ExtensionService
   */
  protected $extensionService;

  /**
   * @var EnvironmentService
   */
  protected $environmentService;

  /**
   * @var ModuleExtensionList
   */
  private $extensionListModule;

  /**
   * SecurityAgentController constructor.
   *
   * @param RequestStack $request
   * @param CacheBackendInterface $cacheBackend
   * @param JsonResponse $jsonResponse
   * @param AccessService $accessService
   * @param ExtensionService $extensionService
   * @param EnvironmentService $environmentService
   * @param ModuleExtensionList $extensionListModule
   */
  public function __construct(RequestStack $request, CacheBackendInterface $cacheBackend, JsonResponse $jsonResponse, AccessService $accessService, ExtensionService $extensionService, EnvironmentService $environmentService, ModuleExtensionList $extensionListModule) {
    $this->request = $request;
    $this->cacheBackend = $cacheBackend;
    $this->jsonResponse = $jsonResponse;
    $this->accessService = $accessService;
    $this->extensionService = $extensionService;
    $this->environmentService = $environmentService;
    $this->extensionListModule = $extensionListModule;
  }

  /**
   * @param ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container) {
    /** @var RequestStack $requestStack */
    $requestStack = $container->get('request_stack');

    /** @var CacheBackendInterface $cacheBackend */
    $cacheBackend = $container->get('cache.default');

    /** @var AccessService $accessService */
    $accessService = $container->get('security_agent.access_service');

    /** @var ExtensionService $extensionService */
    $extensionService = $container->get('security_agent.extension_service');

    /** @var EnvironmentService $environmentService */
    $environmentService = $container->get('security_agent.environment_service');

    /** @var ModuleExtensionList $extensionListModule */
    $extensionListModule = $container->get('extension.list.module');

    return new static(
      $requestStack,
      $cacheBackend,
      new JsonResponse(),
      $accessService,
      $extensionService,
      $environmentService,
      $extensionListModule
    );
  }

  public function get() {
    try {
      $this->accessService->checkKey($this->request->getCurrentRequest()->get('key'));
    }
    catch (\Exception $e) {
      $result = [];
      $result['error'] = $e->getMessage();
      $result['error_code'] = $e->getCode();
      $this->jsonResponse->setData($result);
      return $this->jsonResponse;
    }

    $this->cacheBackend->delete('system.module.info');
    $system =  $this->extensionListModule->getExtensionInfo('system');
    $versionList = [
      'cms' => 'drupal',
      'version' => $system['version'],
      'extension_list' => $this->extensionService->listExtension(),
      'environment' => $this->environmentService->getEnvironment(),
    ];

    $this->jsonResponse->setData($versionList);

    return $this->jsonResponse;
  }
}
