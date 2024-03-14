<?php

namespace platy\etsy\api;

use platy\etsy\GuzzleHttp\Client as GuzzleHttpClient;
use platy\etsy\GuzzleHttp\Exception\BadResponseException;

/**
 * Etsy oAuth client class.
 *
 * @author Rhys Hall hello@rhyshall.com
 */
class Client {

  const CONNECT_URL = "https://www.etsy.com/oauth/connect";
  const TOKEN_URL = "https://api.etsy.com/v3/public/oauth/token";
  const API_URL = "https://openapi.etsy.com/v3/application";

  /**
   * @var string
   */
  protected $client_id;

  /**
   * @var array
   */
  protected $request_headers = [];

  protected $legacy_token;

  protected $api_key;

  /**
   * @var array
   */
  protected $config = [];

  protected $refresh_token;
  /**
   * Create a new instance of Client.
   *
   * @param string $client_id
   * @return void
   */
  public function __construct(
    string $client_id
  ) {
    if(is_null($client_id) || !trim($client_id)) {
      throw new OAuthException("No client ID found. A valid client ID is required.");
    }
    $this->client_id = $client_id;
    $this->api_key = [];
    $this->setApiKey(['oauth2_token' => $this->api_key]);
    $this->legacy_token = null;
  }

  /**
   * Create a new instance of GuzzleHttp Client.
   *
   * @return GuzzleHttp\Client
   */
  public function createHttpClient() {
    return new GuzzleHttpClient();
  }

  /**
   * Sets the client config.
   *
   * @param array $config
   * @return void
   */
  public function setConfig($config) {
    $this->config = $config;
  }

  /**
   * Sets the users API key.
   *
   * @param string $api_key
   * @return void
   */
  public function setApiKey($api_key) {
    $this->headers = [
      'x-api-key' => $this->client_id
    ];
    if(!empty($api_key['oauth2_token'])) {
      $this->headers['Authorization'] = "Bearer {$api_key['oauth2_token']}";
    }
    if(!empty($api_key['oauth2_refresh_token'])) {
      $this->refresh_token = $api_key['oauth2_refresh_token'];
    }
    
  }

  public function set_legacy_token($token) {
    $this->legacy_token = $token;
  }

  public function has_legacty_token_only(){
    return empty($this->api_key) && !empty($this->legacy_token);
  }

  public function __call($method, $args) {
    if(!count($args)) {
      throw new OAuthException("No URI specified for this request. All requests require a URI and optional options array.");
    }
    $valid_methods = ['GET', 'DELETE', 'PATCH', 'POST', 'PUT'];
    if(!in_array($method, $valid_methods)) {
      throw new OAuthException("{$method} is not a valid request method.");
    }
    $uri = $args[0];
    if($method == 'GET' && count($args[1] ?? [])) {
      $uri .= "?".RequestUtil::prepareParameters($args[1]);
    }
    if(in_array($method, ['POST', 'PUT', 'PATCH'])) {
      if($file = RequestUtil::prepareFile($args[1] ?? [])) {
        $opts['multipart'] = $file;
      }
      else {
        $encoding = $args[3];
        $opts[$encoding] = $args[1] ?? [];
      }
    }
    if($method == 'DELETE' && count($args[1] ?? [])) {
      $opts['query'] = $args[1];
    }
    $opts['headers'] = $this->headers;
    try {
      $client = $this->createHttpClient();
      $response = $client->{$method}(self::API_URL.$uri, $opts);

      $response = json_decode($response->getBody(), true);

      return $response;
    }
    catch(\platy\etsy\GuzzleHttp\Exception\RequestException $e) {

      $response = $e->getResponse();
      $body = json_decode($response->getBody(), true);
      $status_code = $response->getStatusCode();

      if(empty($body['error'])) {
        if(!empty($body)) {
          $error = $body[0]['message'];
        }
      }else {
        $error = $body['error'];
      }

      throw new OAuthException(
        "Received HTTP status code [$status_code] with error \"{$error}\".", $status_code
      );
    }catch(\Exception $e) {
      throw new OAuthException($e->getMessage());
    }
  }

  /**
   * Generates the Etsy authorization URL. Your user will use this URL to authorize access for your API to their Etsy account.
   *
   * @param string $redirect_uri
   * @param array $scope
   * @param string $code_challenge
   * @param string $nonce
   * @return string
   */
  public function getAuthorizationUrl(
    string $redirect_uri,
    array $scope,
    $code_challenge,
    $nonce
  ) {
    $params = [
      "response_type" => "code",
      "redirect_uri" => $redirect_uri,
      "scope" => PermissionScopes::prepare($scope),
      "client_id" => $this->client_id,
      "state" => $nonce,
      "code_challenge" => $code_challenge,
      "code_challenge_method" => "S256"
    ];
    return self::CONNECT_URL."/?".RequestUtil::prepareParameters($params);
  }

  /**
   * Requests an authorization token from the Etsy API. Also returns the refresh token.
   *
   * @param string $redirect_uri
   * @param string $code
   * @param string $verifier
   * @return array
   */
  public function requestAccessToken(
    $redirect_uri,
    $code,
    $verifier
  ) {
    $params = [
      "grant_type" => "authorization_code",
      "client_id" => $this->client_id,
      "redirect_uri" => $redirect_uri,
      'code' => $code,
      'code_verifier' => $verifier
    ];
    // Create a GuzzleHttp client.
    $client = $this->createHttpClient();
    try {
      $response = $client->post(self::TOKEN_URL, ['form_params' => $params]);
      $response = json_decode($response->getBody(), false);
      return [
        'access_token' => $response->access_token,
        'refresh_token' => $response->refresh_token
      ];
    }
    catch(\platy\etsy\GuzzleHttp\Exception\RequestException $e) {
      $this->handleAcessTokenError($e);
    }catch(\Exception $e) {
      throw new OAuthException($e->getMessage());
    }
  }

  /**
   * Uses the refresh token to fetch a new access token.
   *
   * @param string $refresh_token
   * @return array
   */
  public function refreshAccessToken(

  ) {
    $params = [
      'grant_type' => 'refresh_token',
      'client_id' => $this->client_id,
      'refresh_token' => $this->refresh_token
    ];
    // Create a GuzzleHttp client.
    $client = $this->createHttpClient();
    try {
      $response = $client->post(self::TOKEN_URL, ['form_params' => $params]);
      $response = json_decode($response->getBody(), false);
      $token = [
        'oauth2_token' => $response->access_token,
        'oauth2_refresh_token' => $response->refresh_token
      ];
      $this->setApiKey($token);
      return $token;
    }
    catch(\Exception $e) {
      throw new OAuthRefreshException("Your token has expired. You need to reauthenticate your app through the shops page.");
    }
  }

  /**
   * Exchanges a legacy OAuth 1.0 token for an OAuth 2.0 token.
   *
   * @param string $legacy_token
   * @return array
   */
  public function exchangeLegacyToken(
    
  ) {
    $legacy_token = $this->legacy_token;
    $params = [
      "grant_type" => "token_exchange",
      "client_id" => $this->client_id,
      "legacy_token" => $legacy_token
    ];
    // Create a GuzzleHttp client.
    $client = $this->createHttpClient();
    try {
      $response = $client->post(self::TOKEN_URL, ['form_params' => $params]);
      $response = json_decode($response->getBody(), false);
      $token = [
        'oauth2_token' => $response->access_token,
        'oauth2_refresh_token' => $response->refresh_token
      ];
      $this->setApiKey($token);
      return $token;
    }
    catch(\Exception $e) {
      $this->handleAcessTokenError($e);
    }
  }


  /**
   * Handles OAuth errors.
   *
   * @param Exception $e
   * @return void
   * @throws OAuthException
   */
  private function handleAcessTokenError(\Exception $e, $def_message = "") {
    $response = $e->getResponse();
    $body = json_decode($response->getBody(), false);
    $status_code = $response->getStatusCode();
    $error_msg = "with error \"{$body->error}\"";
    if($body->error_description ?? false) {
      $error_msg .= "and message \"{$body->error_description}\"";
    }

    if(!empty($def_message)) {
      throw new OAuthException(
        $def_message, $status_code
      );
    }

    throw new OAuthException(
      "Received HTTP status code [$status_code] {$error_msg} when requesting access token.", $status_code
    );
  }

  /**
   * Generates a random string to act as a nonce in OAuth requests.
   *
   * @param int $bytes
   * @return string
   */
  public function createNonce(int $bytes = 12) {
    return bin2hex(random_bytes($bytes));
  }

  /**
   * Generates a PKCE code challenge for use in OAuth requests. The verifier will also be needed for fetching an acess token.
   *
   * @return array
   */
  public function generateChallengeCode() {
    // Create a random string.
    $string = $this->createNonce(32);
    // Base64 encode the string.
    $verifier = $this->base64Encode(
      pack("H*", $string)
    );
    // Create a SHA256 hash and base64 encode the string again.
    $code_challenge = $this->base64Encode(
      pack("H*", hash("sha256", $verifier))
    );
    return [$verifier, $code_challenge];
  }

  /**
   * URL safe base64 encoding.
   *
   * @param string $string
   * @return string
   */
  private function base64Encode($string) {
    return strtr(
      trim(
        base64_encode($string),
        "="
      ),
      "+/", "-_"
    );
  }
} 
