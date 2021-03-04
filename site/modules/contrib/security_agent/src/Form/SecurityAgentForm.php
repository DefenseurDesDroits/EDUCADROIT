<?php
namespace Drupal\security_agent\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SecurityAgentForm extends FormBase {

  /**
   * State API
   *
   * @var StateInterface
   */
  protected $state;

  /**
   * SecurityAgentForm constructor
   *
   * @param StateInterface $state
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'security_agent_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['dns_entry'] = [
      '#type' => 'textfield',
      '#title' => t('DNS entry:'),
      '#required' => FALSE,
      '#description' => t('DNS entry where public keys are stores'),
      '#default_value' => $this->state->get('security_agent.dns_entry')
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
      '#button_type' => 'primary',
    ];

    // By default, render the form using system-config-form.html.twig.
    $form['#theme'] = 'system_config_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->state->set('security_agent.dns_entry', $form_state->getValue('dns_entry'));
  }
}
