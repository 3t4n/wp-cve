<?php
/**
 * Gestion d'utilitaire
 *
 * @author Medialibs
 */
class WeboFacto_Utils {
	/**
	 * Le type de solution utilisé
	 */
	const SITE_TYPE = 'WordPress';

	/**
	 * Instance de l'objet
	 */
	static private $instance;

	/**
	 * L'utilisateur peut-il activer le plugin ?
	 *
	 * @return bool
	 */
	public function canActivatePlugin() {
		return $this->checkAdminRight('activate-plugin_' . $_REQUEST['plugin']);
	}

	/**
	 * L'utilisateur peut-il désactiver le plugin ?
	 *
	 * @return bool
	 */
	public function canDeactivatePlugin() {
		return $this->checkAdminRight('deactivate-plugin_' . $_REQUEST['plugin']);
	}

	/**
	 * Génération d'un mot de passe
	 *
	 * @return string
	 */
	public function generatePassword() {
		return wp_generate_password(20);
	}

	/**
	 * Retourne un identifiant d'utilisateur à partir de son identifiant
	 *
	 * @param  string $username Identifiant
	 *
	 * @return int
	 */
	public static function getUserIdByUsername($username) {
		return intval(username_exists($username));
	}

	/**
	 * Initialisation de la session de l'utilisateur
	 *
	 * @param  int $userId Identifiant de l'utilisateur
	 *
	 * @return null
	 */
	public function initUserSession($userId) {
		if( is_multisite() ) {
			if( !is_super_admin($userId) ) {
				grant_super_admin($userId);
			}
		}
		wp_set_auth_cookie($userId);
	}

	/**
	 * Création d'un compte utilisateur
	 *
	 * @param  array $userData Données de l'utilisateur
	 *
	 * @return int
	 */
	public function createUser($userData) {
		$userId = wp_insert_user(array_map('wp_slash', $userData));

		$user = get_user_by('id', $userId);

		$user->remove_role('subscriber');

		$user->add_role('administrator');

		if( is_multisite() ) {
			if( !is_super_admin($userId) ) {
				grant_super_admin($userId);
			}
		}

		return $userId;
	}

	/**
	 * Get instance
	 *
	 * @return WeboFacto_Utils
	 */
	static public function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * L'utilisateur authentifié est-il un administrateur ?
	 *
	 * @return boolean
	 */
	public function isAdminUser() {
		return current_user_can('editor')
		|| current_user_can('contributor')
		|| current_user_can('administrator')
		|| current_user_can('publish_pages');
	}

	/**
	 * Retourne la page d'authentification
	 *
	 * @return string
	 */
	public function getLoginPage() {
		$login_url = wp_login_url();
		$tab_log = explode("?lang=", $login_url);
		if (isset($tab_log[1]) && strpos($tab_log[1], "en") == 0) {
			$uri_log = explode("en", $tab_log[1]);
			if ($uri_log[1] && isset($uri_log[1])) {
				$url_login = $tab_log[0] . $uri_log[1];
				return $url_login;
			}
		}
		return $login_url;
	}

	/**
	 * Retourne l'URL de l'administration
	 *
	 * @return string
	 */
	public function getAdminPage() {
		return admin_url();
	}

	/**
	 * Est-on sur la page d'authentification ?
	 *
	 * @param  string $pageToTest Page à tester
	 *
	 * @return boolean
	 */
	public function isLoginPage($pageToTest = null) {
		if (!isset($pageToTest) && isset($_SERVER['SCRIPT_URI'])) {
			//Prise en compte url de connexion du genre domain/xxxx/login
			$get_http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
			$current_url = $get_http.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$pageToTest = $current_url;
		}
		if (strpos($pageToTest, $this->getLoginPage()) !== false) {
			return true;
		}
		
		$scriptUri = parse_url($pageToTest);
		$loginPage = parse_url($this->getLoginPage());
		return $scriptUri['path'] === $loginPage['path'];
	}

	/**
	 * L'utilisateur est-il en train de s'authentifier ?
	 *
	 * @return boolean
	 */
	public function isLoginAction() {
		return isset($_POST['log']) && isset($_POST['pwd']);
	}

	/**
	 * Est-on sur une page de l'interface d'administration ?
	 *
	 * @return boolean
	 */
	public function isAdminPage() {
		return is_admin();
	}

	/**
	 * Redirection vers une URL de destination
	 *
	 * @return null
	 */
	public function redirect($redirectionUrl) {
		header('location: ' . $redirectionUrl);
		exit;
	}

	/**
	 * Retourne le server name du projet
	 *
	 * @return string
	 */
	public function getServerName() {
		if (strpos($_SERVER['HOME'], $_SERVER['SERVER_NAME']) !== false) {
			return $_SERVER['SERVER_NAME'];
		}
		if (isset($_SERVER['USER'])) {
			return $_SERVER['USER'];
		}
		return basename(dirname($_SERVER['DOCUMENT_ROOT']));
	}

	/**
	 * Retourne le type du projet
	 *
	 * @return string
	 */
	public function getSiteType() {
		return self::SITE_TYPE;
	}

	/**
	 * Constructuer
	 *
	 * @return null
	 */
	private function __construct() {

	}

	/**
	 * L'adminstrateur peut-il réaliser une action
	 *
	 * @param  string $action Action à réaliser
	 *
	 * @return bool
	 */
	private function checkAdminRight($action) {
		if (!current_user_can('activate_plugins')) {
			return false;
		}
		check_admin_referer($action);
		return true;
	}
}

if (!function_exists('vd')) {
	/**
	 * Var dump propre
	 *
	 * @param mixed   $misc      Données à dumper
	 * @param boolean $forceExit Doit-on faire un exit ?
	 *
	 * @return null
	 */
	function vd($misc, $forceExit = false) {
		echo '<pre>';
		var_dump($misc);
		echo '</pre>';
		if ($forceExit) {
			exit;
		}
	}
}

