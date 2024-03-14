<?php
/**
 * Copyright 2015 Goracash
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Goracash\Service;

use Goracash\Service;
use Goracash\Client as Client;

class Authentication extends Service
{
    public $serviceName = 'Authentication';

    public $servicePath = '/v1/auth/';

    protected $clientSecret;

    protected $clientId;

    protected $token;

    protected $tokenLimit;

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->clientId = $this->client->getClientId();
        $this->token = $this->client->getAccessToken();
        $this->tokenLimit = $this->client->getAccessTokenLimit();
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function authenticate()
    {
        if ($this->token) {
            if ($this->isAvailableToken($this->token)) {
                $this->client->getLogger()->debug('Token is available');
                return;
            }
        }

        $params = array();
        $params['client_id'] = $this->client->getClassConfig('Goracash\Service\Authentication', 'client_id');
        $params['client_secret'] =  $this->client->getClassConfig('Goracash\Service\Authentication', 'client_secret');

        $response = $this->execute('getAccessToken', $params);
        $data = $this->normalize($response);

        $this->client->setAccessToken($data['access_token'], $data['access_token_limit']);
    }

    protected function isAvailableToken($token)
    {
        if ($this->tokenLimit > $this->utils->now()) {
            return true;
        }

        $params = array();
        $params['client_id'] = $this->clientId;
        $params['access_token'] = $token;

        $response = $this->execute('checkAccessToken', $params);
        try {
            $data = $this->normalize($response);
            if ($data['authorized']) {
                $this->client->setAccessToken($data['access_token'], $data['access_token_limit']);
                $this->tokenLimit = $data['limit_date'];
                return true;
            }
            $this->tokenLimit = '';
            $this->client->setAccessToken('');
            return false;
        }
        catch (Exception $e) {
            return false;
        }
    }
}