<?php
  /**
   * @file
   * Contains \Drupal\ddd_forum\Form\ForumConnectionForm.
   **/
  namespace Drupal\ddd_forum\Form;

  use Drupal\Core\Flood\FloodInterface;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Render\RendererInterface;
  use Drupal\user\UserAuthInterface;
  use Drupal\user\UserStorageInterface;
  use Symfony\Component\DependencyInjection\ContainerInterface;

  class ForumConnectionForm extends FormBase {

    /**
     * The flood service.
     *
     * @var \Drupal\Core\Flood\FloodInterface
     */
    protected $flood;

    /**
     * The user storage.
     *
     * @var \Drupal\user\UserStorageInterface
     */
    protected $userStorage;

    /**
     * The user authentication object.
     *
     * @var \Drupal\user\UserAuthInterface
     */
    protected $userAuth;

    /**
     * The renderer.
     *
     * @var \Drupal\Core\Render\RendererInterface
     */
    protected $renderer;

    /**
     * Constructs a new UserLoginForm.
     *
     * @param \Drupal\Core\Flood\FloodInterface $flood
     *   The flood service.
     * @param \Drupal\user\UserStorageInterface $user_storage
     *   The user storage.
     * @param \Drupal\user\UserAuthInterface $user_auth
     *   The user authentication object.
     * @param \Drupal\Core\Render\RendererInterface $renderer
     *   The renderer.
     */
    public function __construct(FloodInterface $flood, UserStorageInterface $user_storage, UserAuthInterface $user_auth, RendererInterface $renderer) {
      $this->flood = $flood;
      $this->userStorage = $user_storage;
      $this->userAuth = $user_auth;
      $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
      return new static(
        $container->get('flood'),
        $container->get('entity.manager')->getStorage('user'),
        $container->get('user.auth'),
        $container->get('renderer')
      );
    }


    public function getFormId() {
      return 'ddd_forum_connection_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
      $form['#theme'] = 'ddd_forum_connection_form';

      $form['email'] = array(
        '#type' => 'textfield',
        '#title' => t('Courriel*'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );

      $form['password'] = array(
        '#type' => 'password',
        '#title' => t('Mot de Passe*'),
        '#title_display' => 'invisible',
        '#attributes' => [
          'class' => ['form-control']
        ]
      );

      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Se Connecter'),
        '#attributes' => [
          'class' => ['btn']
        ]
      );
      return $form;
    }

    public function validateForm(array &$form,  FormStateInterface $form_state) {
      $email = $form_state->getValue('email');
      if (empty($email)) {
        $form_state->setErrorByName('email', '<span class="visually-hidden">'.t('Veuillez renseigner votre courriel').'</span>');
      }
      $password = trim($form_state->getValue('password'));
      if (empty($password)) {
        $form_state->setErrorByName('password', '<span class="visually-hidden">'.t('Veuillez renseigner votre mot de passe').'</span>');
      }
      $flood_config = $this->config('user.flood');
      if (!$form_state->isValueEmpty('email') && strlen($password) > 0) {
        $email = $form_state->getValue('email');
        $account_search = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $email]);
        if ($account = reset($account_search)) {
          $name = $account->getUsername();
        }
        if (empty($name)){
          $form_state->setErrorByName('email', t('Votre email n\'est lié à aucun compte de l\'espace réservé'));
          return;
        }
        // Do not allow any login from the current user's IP if the limit has been
        // reached. Default is 50 failed attempts allowed in one hour. This is
        // independent of the per-user limit to catch attempts from one IP to log
        // in to many different user accounts.  We have a reasonably high limit
        // since there may be only one apparent IP for all users at an institution.
        if (!$this->flood->isAllowed('user.failed_login_ip', $flood_config->get('ip_limit'), $flood_config->get('ip_window'))) {
          $form_state->set('flood_control_triggered', 'ip');
          return;
        }
        $accounts = $this->userStorage->loadByProperties(array('name' => $name, 'status' => 1));
        $account = reset($accounts);
        if ($account) {
          if ($flood_config->get('uid_only')) {
            // Register flood events based on the uid only, so they apply for any
            // IP address. This is the most secure option.
            $identifier = $account->id();
          }
          else {
            // The default identifier is a combination of uid and IP address. This
            // is less secure but more resistant to denial-of-service attacks that
            // could lock out all users with public user names.
            $identifier = $account->id() . '-' . $this->getRequest()->getClientIP();
          }
          $form_state->set('flood_control_user_identifier', $identifier);

          // Don't allow login if the limit for this user has been reached.
          // Default is to allow 5 failed attempts every 6 hours.
          if (!$this->flood->isAllowed('user.failed_login_user', $flood_config->get('user_limit'), $flood_config->get('user_window'), $identifier)) {
            $form_state->set('flood_control_triggered', 'user');
            return;
          }
        }
        // We are not limited by flood control, so try to authenticate.
        // Store $uid in form state as a flag for self::validateFinal().
        if ($uid = $this->userAuth->authenticate($name, $password)){
          $form_state->set('uid', $uid);
        }
        else {
          $form_state->setErrorByName('password', t('Votre mot de passe est incorrect'));
        }
      }
    }

    public function submitForm(array &$form,  FormStateInterface $form_state) {
      $account = $this->userStorage->load($form_state->get('uid'));
      user_login_finalize($account);
    }

    public function validateAuthentication(array &$form, FormStateInterface $form_state) {
      $flood_config = $this->config('user.flood');
      if (!$form_state->get('uid')) {
        // Always register an IP-based failed login event.
        $this->flood->register('user.failed_login_ip', $flood_config->get('ip_window'));
        // Register a per-user failed login event.
        if ($flood_control_user_identifier = $form_state->get('flood_control_user_identifier')) {
          $this->flood->register('user.failed_login_user', $flood_config->get('user_window'), $flood_control_user_identifier);
        }

        if ($flood_control_triggered = $form_state->get('flood_control_triggered')) {
          if ($flood_control_triggered == 'user') {
            drupal_set_message(t('There have been too many failed login attempts for this account. It is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', array(':url' => $this->url('user.pass'))), 'error');
          }
          else {
            // We did not find a uid, so the limit is IP-based.
            drupal_set_message(t('Too many failed login attempts from your IP address. This IP address is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', array(':url' => $this->url('user.pass'))), 'error');
          }
        }
        else {
          // Use $form_state->getUserInput() in the error message to guarantee
          // that we send exactly what the user typed in. The value from
          // $form_state->getValue() may have been modified by validation
          // handlers that ran earlier than this one.
          $user_input = $form_state->getUserInput();
          $query = isset($user_input['email']) ? array('email' => $user_input['email']) : array();
          // drupal_set_message('Unrecognized username or password');
          drupal_set_message(t('Unrecognized username or password. <a href=":password">Forgot your password?</a>', array(':password' => $this->url('user.pass', [], array('query' => $query)))), 'error');
          $accounts = $this->userStorage->loadByProperties(array('email' => $form_state->getValue('email')));
          if (!empty($accounts)) {
            $this->logger('user')->notice('Login attempt failed for %email.', array('%email' => $form_state->getValue('email')));
          }
          else {
            // If the username entered is not a valid user,
            // only store the IP address.
            $this->logger('user')->notice('Login attempt failed from %ip.', array('%ip' => $this->getRequest()->getClientIp()));
          }
        }
      }
      elseif ($flood_control_user_identifier = $form_state->get('flood_control_user_identifier')) {
        // Clear past failures for this user so as not to block a user who might
        // log in and out more than once in an hour.
        $this->flood->clear('user.failed_login_user', $flood_control_user_identifier);
      }
    }

    public function validateFinal(array &$form, FormStateInterface $form_state) {
      $flood_config = $this->config('user.flood');
      if (!$form_state->get('uid')) {
        // Always register an IP-based failed login event.
        $this->flood->register('user.failed_login_ip', $flood_config->get('ip_window'));
        // Register a per-user failed login event.
        if ($flood_control_user_identifier = $form_state->get('flood_control_user_identifier')) {
          $this->flood->register('user.failed_login_user', $flood_config->get('user_window'), $flood_control_user_identifier);
        }

        if ($flood_control_triggered = $form_state->get('flood_control_triggered')) {
          if ($flood_control_triggered == 'user') {
            $form_state->setErrorByName('name', $this->formatPlural($flood_config->get('user_limit'), 'There has been more than one failed login attempt for this account. It is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', 'There have been more than @count failed login attempts for this account. It is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', array(':url' => $this->url('user.pass'))));
          }
          else {
            // We did not find a uid, so the limit is IP-based.
            $form_state->setErrorByName('name', $this->t('Too many failed login attempts from your IP address. This IP address is temporarily blocked. Try again later or <a href=":url">request a new password</a>.', array(':url' => $this->url('user.pass'))));
          }
        }
        else {
          // Use $form_state->getUserInput() in the error message to guarantee
          // that we send exactly what the user typed in. The value from
          // $form_state->getValue() may have been modified by validation
          // handlers that ran earlier than this one.
          $user_input = $form_state->getUserInput();
          $query = isset($user_input['email']) ? array('email' => $user_input['email']) : array();
          $form_state->setErrorByName('email', $this->t('Unrecognized email or password. <a href=":password">Forgot your password?</a>', array(':password' => $this->url('user.pass', [], array('query' => $query)))));
          $accounts = $this->userStorage->loadByProperties(array('email' => $form_state->getValue('email')));
          if (!empty($accounts)) {
            $this->logger('user')->notice('Login attempt failed for %email.', array('%email' => $form_state->getValue('email')));
          }
          else {
            // If the username entered is not a valid user,
            // only store the IP address.
            $this->logger('user')->notice('Login attempt failed from %ip.', array('%ip' => $this->getRequest()->getClientIp()));
          }
        }
      }
      elseif ($flood_control_user_identifier = $form_state->get('flood_control_user_identifier')) {
        // Clear past failures for this user so as not to block a user who might
        // log in and out more than once in an hour.
        $this->flood->clear('user.failed_login_user', $flood_control_user_identifier);
      }
    }
  }
