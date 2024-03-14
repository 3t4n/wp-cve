<?php
/**
 * Gestion des appels SOAP vers le webo-facto
 *
 * @author Medialibs
 */
class WeboFacto_Soap extends SoapClient
{
	/**
	 * Serveur SOAP
	 */
	const SOAP_LOCATION = 'https://www.webo-facto.com/emajine_ws-auth';

	/**
	 * URI utilisé
	 */
	const SOAP_URI      = 'urn:emajine_auth';

	/**
	 * Clé pour le cryptage
	 */
	const CRYPT_KEY     = '3351d59fdf2f6003feac61726249b17c';

	/**
	 * Module pour le cryptage
	 */
	const CRYPT_MODULE  = 'DES-ECB';

	/**
	 * Constructuer
	 *
	 * @return null
	 */
	public function __construct()
	{
		parent::__construct(null, array('location' => static::SOAP_LOCATION,
										'uri'      => static::SOAP_URI));
	}

	/**
	 * Vérification du token SSO et récupération des données de l'utilisateur
	 *
	 * @param  string $username   L'identifiant utilisateur
	 * @param  string $token      Token d'authentification
	 * @param  string $ip         Adresse IP courante
	 * @param  string $servername La référence du site
	 * @param  string $siteType   Type de site
	 * @param  string $version    Version du site
	 * @param  string $loginUrl   Url pour la connexion
	 *
	 * @return array
	 */
	public function callCheckTokenAndGetUserData($username, $token, $ip, $servername, $siteType, $version, $loginUrl)
	{
		try {
			$param = new SoapVar($this->encrypt(implode('@', func_get_args())), XSD_STRING);
			$data  = json_decode($this->decrypt($this->checkTokenAndGetUserData($param)));
			if ($data->error) {
				return [];
			}
			return (array) $data->data;
		} catch (Exception $e) {
			if (WP_DEBUG) {
				echo '<h1>Plugin Webo-facto Error</h1>';
				echo 'Method: callCheckTokenAndGetUserData<br />
					  Error message : ' . $e->getMessage() . '<br />
					  Backtrace : <pre>';
				debug_print_backtrace();
				echo '</pre>';
				exit;
			}
			return [];
		}
	}

	/**
	 * Mise à jour des données du site
	 *
	 * @param  string $servername La référence du site
	 * @param  string $siteType   Type de site
	 * @param  string $version    Version du site
	 * @param  string $loginUrl   Url pour la connexion
	 *
	 * @return bool
	 */
	public function updateSite($servername, $siteType, $version, $loginUrl)
	{
		try {
			$param = new SoapVar($this->encrypt(implode('@', func_get_args())), XSD_STRING);
			$data  = json_decode($this->decrypt($this->updateSiteData($param)));
			if ($data->error) {
				return false;
			}
			return true;
		} catch (Exception $e) {
			if (WP_DEBUG) {
				echo '<h1>Plugin Webo-facto Error</h1>';
				echo 'Method: updateSite<br />
					  Error message : ' . $e->getMessage() . '<br />
					  Backtrace : <pre>';
				debug_print_backtrace();
				echo '</pre>';
				exit;
			}
	    }
	    return false;
	}

	/**
	 * Encrypte les données
	 *
	 * @param string $input Données à crypter
	 *
	 * @return string
	 */
	public function encrypt($input)
	{
		return openssl_encrypt('000CRYPTO000' . base64_encode($input), self::CRYPT_MODULE, self::CRYPT_KEY, 0);
	}

	/**
	 * Décrypte les données
	 *
	 * @param string $input Données à décrypter
	 *
	 * @return string
	 */
	public function decrypt($input)
	{
		$output = trim(openssl_decrypt($input, self::CRYPT_MODULE, self::CRYPT_KEY, OPENSSL_ZERO_PADDING));

		if ('000CRYPTO000' == substr($output, 0, 12)) {
			$output = base64_decode(substr($output, 12));
		}

		return $output;
	}
}
