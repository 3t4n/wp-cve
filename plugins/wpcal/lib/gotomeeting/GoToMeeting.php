<?php

namespace WPCal\ComposerPackages\League\OAuth2\Client\Provider;

include_once __DIR__ . '/../composer_packages/vendor/autoload.php';
include_once __DIR__ . '/GoToMeetingResourceOwner.php';
//include_once(__DIR__.'/GoToMeetingMeetingResource.php');
use WPCal\ComposerPackages\League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use WPCal\ComposerPackages\League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use WPCal\ComposerPackages\League\OAuth2\Client\Token\AccessToken;
use WPCal\ComposerPackages\League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use WPCal\ComposerPackages\Psr\Http\Message\ResponseInterface;

class GoToMeeting extends \WPCal\ComposerPackages\League\OAuth2\Client\Provider\AbstractProvider {
	use BearerAuthorizationTrait;
	/**
	 * @var string Key used in a token response to identify the resource owner.
	 */
	const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'user.id';
	// /**
	//  * Default scopes
	//  *
	//  * @var array
	//  */
	// public $defaultScopes = ['basic'];
	/**
	 * Default host
	 *
	 * @var string
	 */
	protected $host = 'https://api.getgo.com/G2M/rest/';
	private $apiToken = '';
	private $access = '';
	public function __construct($options) {
		$parent_collaborators = ['optionProvider' => new \WPCal\ComposerPackages\League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider()];
		parent::__construct($options, $parent_collaborators);
	}
	/**
	 * Gets host.
	 *
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}
	/**
	 * Get the string used to separate scopes.
	 *
	 * @return string
	 */
	protected function getScopeSeparator() {
		return ' ';
	}
	/**
	 * Get authorization url to begin OAuth flow
	 *
	 * @return string
	 */
	public function getBaseAuthorizationUrl() {
		return 'https://api.getgo.com/oauth/v2/authorize';
	}
	/**
	 * Get access token url to retrieve token
	 *
	 * @param  array $params
	 *
	 * @return string
	 */
	public function getBaseAccessTokenUrl(array $params) {
		return 'https://api.getgo.com/oauth/v2/token';
	}
	/**
	 * Get provider url to fetch user details
	 *
	 * @param  AccessToken $token
	 *
	 * @return string
	 */
	public function getResourceOwnerDetailsUrl(\WPCal\ComposerPackages\League\OAuth2\Client\Token\AccessToken $token) {
		$url = "https://api.getgo.com/admin/rest/v1/me";
		//$url .= "&fields=first_name%2Cid%2Clast_name%2Curl%2Cimage%2Cusername%2Ccreated_at%2Ccounts";
		return $url;
	}
	// protected function getAccessTokenRequest(array $params)
	// {
	//     $method  = $this->getAccessTokenMethod();
	//     $url     = $this->getAccessTokenUrl($params);
	//     $options = $this->optionProvider->getAccessTokenOptions($this->getAccessTokenMethod(), $params);
	//     $url = $url. '?' .$options['body'];
	//     unset($options['body']);
	//     $request = $this->getRequest($method, $url, $options);
	//     var_dump($request);
	//     return $request;
	// }
	public function setAPIToken($token) {
		$this->apiToken = new \WPCal\ComposerPackages\League\OAuth2\Client\Token\AccessToken($token);
	}
	public function getAPIToken($token) {
		return $this->apiToken;
	}
	public function isAccessTokenExpired() {
		$expired = $this->apiToken->hasExpired();
		if (!$expired) {
			$expires = $this->apiToken->getExpires();
			return $expires < \time() - 30;
		}
		return $expired;
	}
	public function fetchAccessTokenWithRefreshToken() {
		$newAccessToken = $this->getAccessToken('refresh_token', ['refresh_token' => $this->apiToken->getRefreshToken()]);
		$this->apiToken = $newAccessToken;
		return $newAccessToken;
	}
	// protected function getAuthorizationHeaders($token = null){
	//     if( $token ){
	//     }
	// }
	/**
	 * Returns an authenticated PSR-7 request instance.
	 *
	 * @param  string $method
	 * @param  string $url
	 * @param  AccessToken|string $token
	 * @param  array $options Any of "headers", "body", and "protocolVersion".
	 *
	 * @return \Psr\Http\Message\RequestInterface
	 */
	// public function getAuthenticatedRequest($method, $url, $token, array $options = [])
	// {
	//     // $parsedUrl = parse_url($url);
	//     // $queryString = array();
	//     // if (isset($parsedUrl['query'])) {
	//     //     parse_str($parsedUrl['query'], $queryString);
	//     // }
	//     // if (!isset($queryString['access_token'])) {
	//     //     $queryString['access_token'] = (string) $token;
	//     // }
	//     // $url = http_build_url($url, [
	//     //     'query' => http_build_query($queryString),
	//     // ]);
	//     $request = $this->createRequest($method, $url, $token, $options);
	//     var_dump($request);
	//     return $request;
	// }
	/**
	 * Get the default scopes used by this provider.
	 *
	 * This should not be a complete list of all scopes, but the minimum
	 * required for the provider user interface!
	 *
	 * @return array
	 */
	protected function getDefaultScopes() {
		return [];
	}
	/**
	 * Check a provider response for errors.
	 *
	 * @throws IdentityProviderException
	 * @param  ResponseInterface $response
	 * @param  string $data Parsed response data
	 * @return void
	 */
	protected function checkResponse(\WPCal\ComposerPackages\Psr\Http\Message\ResponseInterface $response, $data) {
		// var_dump($response);
		// var_dump($data);
		if ($response->getStatusCode() >= 400) {
			throw new \WPCal\ComposerPackages\League\OAuth2\Client\Provider\Exception\IdentityProviderException(!empty($data['message']) ? $data['message'] : $response->getReasonPhrase(), $response->getStatusCode(), $response);
		}
	}
	/**
	 * Generate a user object from a successful user details request.
	 *
	 * @param array $response
	 * @param AccessToken $token
	 * @return ResourceOwnerInterface
	 */
	protected function createResourceOwner(array $response, \WPCal\ComposerPackages\League\OAuth2\Client\Token\AccessToken $token) {
		return new \WPCal\ComposerPackages\League\OAuth2\Client\Provider\GoToMeetingResourceOwner($response);
	}
	// protected function getDefaultHeaders()
	// {
	//   return [ 'Content-Type' => 'application/json', 'Accept' => 'application/json', 'User-Agent' => 'wpcal/oauth2-gotomeeting/1.0.0' ];
	// }
	/**
	 * Sets host.
	 *
	 * @param string $host
	 *
	 * @return string
	 */
	public function setHost($host) {
		$this->host = $host;
		return $this;
	}
	public function getResourceOwnerDetails() {
		$user = $this->getResourceOwner($this->apiToken);
		return $user;
	}
	public function createMeeting($details) {
		$method = 'POST';
		$url = $this->getHost() . 'meetings';
		$default_args = [];
		$body = \array_merge($default_args, $details);
		$options = ['body' => \json_encode($body)];
		$request = $this->getAuthenticatedRequest($method, $url, $this->apiToken, $options);
		// var_dump($request );
		$response = $this->getParsedResponse($request);
		return $response;
	}
	public function updateMeeting($details, $meetingID) {
		$method = 'PUT';
		$url = $this->getHost() . 'meetings/' . $meetingID;
		$default_args = [];
		$body = \array_merge($default_args, $details);
		$options = ['body' => \json_encode($body)];
		$request = $this->getAuthenticatedRequest($method, $url, $this->apiToken, $options);
		// var_dump($request );
		$response = $this->getParsedResponse($request);
		return $response;
	}
	public function deleteMeeting($meetingID) {
		$method = 'DELETE';
		$url = $this->getHost() . 'meetings/' . $meetingID;
		$options = [];
		$request = $this->getAuthenticatedRequest($method, $url, $this->apiToken, $options);
		// var_dump($request );
		$response = $this->getParsedResponse($request);
		return $response;
	}
	public function getMeeting($meetingID) {
		$method = 'GET';
		$url = $this->getHost() . 'meetings/' . $meetingID;
		$options = [];
		$request = $this->getAuthenticatedRequest($method, $url, $this->apiToken, $options);
		// var_dump($request );
		$response = $this->getParsedResponse($request);
		return $response;
	}
	public function revokeToken($token_type = 'access') {
		if ($token_type == 'access') {
			$token = $this->apiToken->getToken();
		} elseif ($token_type == 'refresh') {
			$token = $this->apiToken->getRefreshToken();
		} else {
			throw new \Exception('invalid_token_type');
		}
		$url_params = ['token' => $token];
		$method = 'POST';
		$url = 'https://api.getgo.com/oauth/v2/revoke?' . \http_build_query($url_params);
		$params = ['client_id' => $this->clientId, 'client_secret' => $this->clientSecret, 'redirect_uri' => $this->redirectUri];
		$options = $this->optionProvider->getAccessTokenOptions($method, $params);
		//to get header authorization with client_id and client_secret
		$request = $this->getRequest($method, $url, $options);
		// var_dump($request);
		$response = $this->getResponse($request);
		if ($response->getStatusCode() == 200) {
			return \true;
		} else {
			return \false;
		}
	}
}
