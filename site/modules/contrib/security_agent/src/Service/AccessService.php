<?php
namespace Drupal\security_agent\Service;

use Drupal\Core\State\StateInterface;
use Drupal\security_agent\Service\AccessServiceException;
use Symfony\Component\HttpFoundation\RequestStack;

class AccessService {

  /**
   * Request stack
   *
   * @var RequestStack
   */
  protected $request;

  /**
   * Request stack
   *
   * @var StateInterface
   */
  protected $state;

  /**
   * AccessService constructor
   *
   * @param RequestStack $request
   * @param StateInterface $state
   */
  public function __construct(RequestStack $request, StateInterface $state) {
    $this->request = $request;
    $this->state = $state;
  }

  /**
   * @throws AccessServiceException
   */
  public function checkKey($key) {
    $clientIp = $this->request->getCurrentRequest()->getClientIp();
    $securityAgentDNSEntry = $this->state->get('security_agent.dns_entry', 'security-agent-key.gaya.fr');
    $dnsLookup = dns_get_record($securityAgentDNSEntry, DNS_TXT);

    if (!isset($dnsLookup[0]) || empty($dnsLookup[0]['txt'])) {
      throw new AccessServiceException('No key found in TXT entry for domain ' . $securityAgentDNSEntry, AccessServiceException::KEY_NOT_ALLOWED);
    } else {
      $publicKeys = explode(',', $dnsLookup[0]['txt']);
      $clientKey = md5($clientIp . '-' . $key);
      if (!in_array($clientKey, $publicKeys)) {
        throw new AccessServiceException('DNS entry missing for client ' . $clientIp . ' : ' . $clientKey, AccessServiceException::KEY_NOT_ALLOWED);
      }
    }
  }
}
