<?php
/**
 * Gestion de la connexion SSO avec le webo-facto
 *
 * @author Medialibs
 */
class WeboFacto_Sso {
	/**
	 * Durée entre 2 vérifications de connexion SSO
	 */
	const SSO_CHECK_DURATION = 10;

	/**
	 * URL du service d'authentification SSO
	 */
	const SSO_WEBO_FACTO_SERVICE = 'https://www.webo-facto.com/AUTH_SSO/?REDIRECT=%s';

	/**
	 * URL de déconnexion Webo-facto
	 */
	const LOGOUT_WEBO_FACTO_SERVICE = 'https://www.webo-facto.com/?logout=1&ssoRedirect=%s';

	/**
	 * Constructeur
	 *
	 * @return null
	 */
	public function __construct() {

	}

	/**
	 * Initialisation
	 *
	 * @return null
	 */
	public function init() {
		add_action('wp_loaded', [$this, 'doSsoAuthentification']);

		add_action('wp_logout', [$this, 'doWeboFactoLogout']);
	}

	/**
	 * Gestion de la déconnexion
	 *
	 * @return null
	 */
	public function doWeboFactoLogout() {
		$woo_logout = get_permalink( get_option('woocommerce_myaccount_page_id') );
		$parsed_permalink = explode('/', $woo_logout );
		$account = ('' == end($parsed_permalink ) ) ? prev( $parsed_permalink):null;
		$currentUser = wp_get_current_user();
		if ($currentUser->ID && $currentUser->ID != 0) {
			if (strpos($currentUser->data->user_login, '-wfuser') !== false) {
				WeboFacto_Utils::getInstance()->redirect(sprintf(self::LOGOUT_WEBO_FACTO_SERVICE, urlencode($this->getRedirectionUrl())));
			}			
		}
		if( $account && false !== strpos($_SERVER['REQUEST_URI'], $account) ){
			$_REQUEST['redirect_to'] = $this->getRedirectionUrl($woo_logout);
		}
		else {
			$_REQUEST['redirect_to'] = $this->getRedirectionUrl(WeboFacto_Utils::getInstance()->getLoginPage());
		}
	}

	/**
	 * Gestion de l'authentification de l'authentification SSO
	 *
	 * @return null
	 */
	public function doSsoAuthentification() {
		$weboFactoUtilsInstance = WeboFacto_Utils::getInstance();
		$wc_account_permalink = get_permalink( get_option('woocommerce_myaccount_page_id'));
		$parsed_permalink = explode('/', $wc_account_permalink );
		$account = ('' == end($parsed_permalink ) ) ? prev( $parsed_permalink):'';
		//Si l'utilisateur est connecté et n'est pas admin ou si on est sur le site WordPress,sur l'URL ajax ou sur le rappel de mot de passe, ou sur le tableau de bord woocommerce on ne fait rien
		if ((is_user_logged_in() && !$weboFactoUtilsInstance->isAdminUser())
			|| (!$weboFactoUtilsInstance->isAdminPage() && !$weboFactoUtilsInstance->isLoginPage()) 
			|| ( $account && false !== strpos($_SERVER['REQUEST_URI'], $account) )			
			|| strpos($_SERVER['REQUEST_URI'], '/admin-ajax.php') !== false
			|| (isset($_GET['action']) && ($_GET['action'] === 'lostpassword'))
			|| (!post_password_required() && isset($_GET['action']) && ($_GET['action'] === 'postpass')))
		{
			return null;
		}

		// URL appelée par le webo-facto pour récupérer la version et l'URL pour l'authentification
		if (isset($_GET['updateVersionAndLoginUrl'])) {
			$this->updateVersionAndLoginUrl();
		}

		// Si l'utilisateur est en train d'authentifier, on ne fait rien
		if ($weboFactoUtilsInstance->isLoginAction()) {
			return null;
		}

		// Un administrateur est déjà authentifié
		if ($weboFactoUtilsInstance->isAdminUser()) {
			// Si on est sur la page d'authentification, on le redirige vers l'administration
			if ($weboFactoUtilsInstance->isLoginPage()
				&& (!isset($_GET['action']) || $_GET['action'] !== 'logout')
			) {
				$weboFactoUtilsInstance->redirect($weboFactoUtilsInstance->getAdminPage());
			}
			// Sinon, on ne fait rien
			return null;
		}
		if (isset($_GET['amp;lastSsoCheck']) && !empty($_GET['amp;lastSsoCheck'])) {
			$_GET['lastSsoCheck'] = $_GET['amp;lastSsoCheck'];
			unset($_GET['amp;lastSsoCheck']);
		}

		// L'authentification webo-facto est en cours
		if (isset($_GET['AUTH_SSO'])) {
			$redirectUrl = $this->prepareRedirectUrl($_GET['REDIRECT']);
			$userData = $this->checkSsoAndGetUserData();
			if (!empty($userData)) {
				$this->authenticateAdmin($userData);
				if ($weboFactoUtilsInstance->isLoginPage($redirectUrl)) {
					$redirectUrl = $weboFactoUtilsInstance->getAdminPage();
				}
			}
			$weboFactoUtilsInstance->redirect($redirectUrl);
		}

		// Vérification d'une authentification webo-facto existante
		$this->isUserAuthenticated();
	}

	/**
	 * Mise à jour de la version et de l'URL pour l'authentification dans le webo-facto
	 *
	 * @return null
	 */
	private function updateVersionAndLoginUrl()
	{
		(new WeboFacto_Soap())->updateSite(WeboFacto_Utils::getInstance()->getServerName(),
										   WeboFacto_Utils::getInstance()->getSiteType(),
										   get_bloginfo('version'),
										   WeboFacto_Utils::getInstance()->getLoginPage());
		exit;
	}

	/**
	 * Authentifier l'administrateur
	 *
	 * @param  array $userData Données de l'utilisateur authentifié
	 *
	 * @return null
	 */
	private function authenticateAdmin($userData) {
		$username = explode('%', urldecode($_GET['AUTH_USERNAME']))[0] . '-wfuser';

		$userId = WeboFacto_Utils::getUserIdByUsername($username);
		if ($userId === 0) {
			$userId = $this->createAdmin($username, $userData);
		}

		WeboFacto_Utils::getInstance()->initUserSession($userId);
	}

	/**
	 * Création d'un administrateur
	 *
	 * @param string $username Identifiant de l'utilisateur
	 * @param array  $userData Données de l'utilisateur authentifié
	 *
	 * @return int
	 */
	private function createAdmin($username, $userData) {
		return WeboFacto_Utils::getInstance()->createUser(['user_login' => $username,
			'user_email' => $username . '@' . WeboFacto_Utils::getInstance()->getServerName(),
			'user_pass' => WeboFacto_Utils::getInstance()->generatePassword(),
			'first_name' => $userData['firstName'],
			'last_name' => $userData['lastName'],
			'nickname' => $userData['name']]);
	}

	/**
	 * Vérification des informations d'authentification transmises par le webo-facto
	 *
	 * @return array
	 */
	private function checkSsoAndGetUserData() {
		$username = urldecode($_GET['AUTH_USERNAME']);
		$token = urldecode($_GET['AUTH_TOKEN']);
		$ip = $this->getIp();

		// Si on ne dispose pas des informations, l'authentification SSO est en échec
		if (empty($username) || empty($token) || empty($ip)) {
			return [];
		}

		return (new WeboFacto_Soap())->callCheckTokenAndGetUserData($username,
			$token,
			$ip,
			WeboFacto_Utils::getInstance()->getServerName(),
			WeboFacto_Utils::getInstance()->getSiteType(),
			get_bloginfo('version'),
			WeboFacto_Utils::getInstance()->getLoginPage());
	}

	/**
	 * Préparation de l'URL de redirection après check SSO
	 *
	 * @param string $rawUrl URL brute
	 *
	 * @return string
	 */
	private function prepareRedirectUrl($rawUrl) {
		if (empty($rawUrl)) {
			return '';
		}

		return urldecode($rawUrl);
	}

	/**
	 * Vérifie si l'utilisateur est authentifié
	 *
	 * @return null
	 */
	private function isUserAuthenticated() {
		if (!$this->shouldCheckSso()) {
			return null;
		}
		WeboFacto_Utils::getInstance()->redirect(sprintf(self::SSO_WEBO_FACTO_SERVICE, urlencode($this->getRedirectionUrl())));
	}

	/**
	 * Retourne l'URL vers laquelle rediriger l'utilisateur après vérification SSO
	 *
	 * @param string $uri L'URI
	 *
	 * @return string
	 */
	private function getRedirectionUrl($uri = '') {
		if (empty($uri)) {
			$uri = $_SERVER['REQUEST_URI'];
		}
		$parsedUrl = parse_url($uri);
		$vars = [];
		if (!empty($parsedUrl['query'])) {
			parse_str($parsedUrl['query'], $vars);
			if (isset($vars['lastSsoCheck'])) {
				unset($vars['lastSsoCheck']);
			}
			if (isset($vars['amp;lastSsoCheck'])) {
				unset($vars['amp;lastSsoCheck']);
			}
			if (isset($vars['action']) && $vars['action'] === 'logout') {
				unset($vars['action']);
			}
			if (isset($vars['_wpnonce'])) {
				unset($vars['_wpnonce']);
			}
		}
		$vars['lastSsoCheck'] = time();
		return 'http' . (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $parsedUrl['path'] . '?' . http_build_query($vars);
	}

	/**
	 * Doit-on faire un check SSO
	 *
	 * @return bool
	 */
	private function shouldCheckSso() {
		if (isset($_GET['action']) && ("rp" == $_GET['action'] || "resetpass" == $_GET['action'])) {
			return false;
		}

		return !isset($_GET['lastSsoCheck']) || intval($_GET['lastSsoCheck']) < (time() - self::SSO_CHECK_DURATION);
	}

	/**
	 * Retourne l'adresse IP de l'utilisateur
	 *
	 * @return string $ip
	 */
	private function getIp() {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}
}

