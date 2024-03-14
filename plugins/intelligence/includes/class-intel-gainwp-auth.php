<?php
/*
 * Copyright 2008 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if (!class_exists('Deconfin_Client')) {
  require_once dirname(__FILE__) . '/../autoload.php';
}

/**
 * Authentication class that deals with the OAuth 2 web-server authentication flow
 *
 */
class Intel_Gainwp_Auth extends Deconfin_Auth_OAuth2 {

  public function setClient(Deconfin_Client $client) {
    $this->client = $client;
  }

  /**
   * Fetches a fresh access token with the given refresh token.
   * @param string $refreshToken
   * @return void
   */
  public function refreshToken($refreshToken) {
    $this->refreshTokenRequest( array() );

    return;
    /*
    if (!empty($refreshToken)) {
      parent::refreshToken($refreshToken);
    }
    else {
      $this->refreshTokenRequest( array() );
    }
    */

  }

  /**
   * Fetches a fresh access token with a given assertion token.
   * @param Deconfin_Auth_AssertionCredentials $assertionCredentials optional.
   * @return void
   */
  public function refreshTokenWithAssertion($assertionCredentials = null)
  {
    if (!$assertionCredentials) {
      $assertionCredentials = $this->assertionCredentials;
    }

    $cacheKey = $assertionCredentials->getCacheKey();

    if ($cacheKey) {
      // We can check whether we have a token available in the
      // cache. If it is expired, we can retrieve a new one from
      // the assertion.
      $token = $this->client->getCache()->get($cacheKey);
      if ($token) {
        $this->setAccessToken($token);
      }
      if (!$this->isAccessTokenExpired()) {
        return;
      }
    }

    $this->client->getLogger()->debug('OAuth2 access token expired');
    $this->refreshTokenRequest(
      array(
        'grant_type' => 'assertion',
        'assertion_type' => $assertionCredentials->assertionType,
        'assertion' => $assertionCredentials->generateAssertion(),
      )
    );

    if ($cacheKey) {
      // Attempt to cache the token.
      $this->client->getCache()->set(
        $cacheKey,
        $this->getAccessToken()
      );
    }
  }

  /*
   * Override refreshTokenRequest to fetch token from IMAPI
   */
  private function refreshTokenRequest($params) {
    include_once INTEL_DIR . 'includes/intel.imapi.php';

    $this->client->getLogger()->info('Intel_Auth access token refresh');

    $token = intel_imapi_ga_access_token_get();

    if (! isset($token['access_token']) || ! isset($token['expires_in'])) {
      throw new Deconfin_Auth_Exception("Invalid token format");
    }

    if (isset($token['id_token'])) {
      $this->token['id_token'] = $token['id_token'];
    }
    if (isset($token['refresh_token'])) {
      $this->token['refresh_token'] = $token['refresh_token'];
    }
    $this->token['access_token'] = $token['access_token'];
    $this->token['expires_in'] = $token['expires_in'];
    $this->token['created'] = time();
    if (isset($token['created_ago']) && is_numeric($token['created_ago'])) {
      $this->token['created'] -= $token['created_ago'];
    }

Intel_Df::watchdog('Intel_Gainwp_Auth::refreshTokenRequest this->token', print_r($token, 1));
  }

}
