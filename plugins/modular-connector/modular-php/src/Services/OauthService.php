<?php

namespace Modular\SDK\Services;

use Modular\SDK\Objects\OauthToken;

class OauthService extends AbstractService
{
    /**
     * @param string $code
     * @param array $opts
     * @return \Modular\SDK\Objects\OauthToken
     * @throws \ErrorException
     */
    public function confirmAuthorizationCode(string $code, array $opts = []): OauthToken
    {
        return $this->request('post', $this->buildPath('oauth/token'), [
            'grant_type' => 'authorization_code',
            'client_id' => $this->getClient()->getClientId(),
            'client_secret' => $this->getClient()->getClientSecret(),
            'code' => $code,
            'redirect_uri' => $this->getClient()->getClientRedirectUri()
        ], $opts);
    }

    /**
     * @param array $opts
     * @return \Modular\SDK\Objects\OauthToken
     * @throws \ErrorException
     */
    public function refreshToken(array $opts = []): OauthToken
    {
        return $this->request('post', $this->buildPath('oauth/token'), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->getClient()->getRefreshToken(),
            'client_id' => $this->getClient()->getClientId(),
            'client_secret' => $this->getClient()->getClientSecret(),
            'scope' => '',
        ], $opts);
    }


    /**
     * @return void
     * @throws \ErrorException
     */
    public function renewAccessToken()
    {
        $token = $this->refreshToken();

        $this->getClient()
            ->setAccessToken($token->access_token)
            ->setRefreshToken($token->refresh_token)
            ->setExpiresIn($token->expires_in)
            ->save();
    }
}
