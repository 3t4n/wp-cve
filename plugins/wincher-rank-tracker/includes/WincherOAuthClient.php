<?php

namespace Wincher;

use Exception;
use function get_site_url;
use Wincher_Vendor\League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Wincher_Vendor\League\OAuth2\Client\Token\AccessToken;
use Wincher\Providers\PKCEProvider;
use function wp_parse_url;

/**
 * The Wincher OAuth client.
 */
class WincherOAuthClient
{
    /**
     * @var string
     */
    public const TOKEN_OPTION = 'wincher_oauth_token';

    /**
     * Name of the temporary PKCE transient.
     *
     * @var string
     */
    public const PKCE_TRANSIENT_NAME = 'wincher_pkce';

    /**
     * The base URI.
     *
     * @var string
     */
    public const BASE_URI = 'https://api.wincher.com/beta/';

    /**
     * @var AccessToken
     */
    protected $tokens;

    /**
     * @var PKCEProvider
     */
    private $provider;
    /**
     * @var array|false|mixed|null
     */
    private $domain;

    public function __construct()
    {
        $this->tokens = get_option(self::TOKEN_OPTION, null);
        // Fix for tokens that were serialized in 3.0.4, but to be deserialized in 3.0.5
        if ($this->tokens != null && get_class($this->tokens) == '__PHP_Incomplete_Class') {
            $this->tokens = $this->cast_to_class('Wincher_Vendor\League\OAuth2\Client\Token\AccessToken', $this->tokens);
        }

        $this->domain = WP_DEBUG === true ? wp_parse_url('https://wincher.com') : wp_parse_url(get_site_url());

        $this->provider = new PKCEProvider(
            [
                'clientId' => 'wincher-wp',
                'redirectUri' => 'https://auth.wincher.com/callback/wp',
                'urlAuthorize' => 'https://auth.wincher.com/connect/authorize',
                'urlAccessToken' => 'https://auth.wincher.com/connect/token',
                'urlResourceOwnerDetails' => 'https://api.wincher.com/beta/user',
                'scopes' => [
                    'profile',
                    'account',
                    'websites:read',
                    'websites:write',
                    'offline_access',
                ],
                'scopeSeparator' => ' ',
                'pkceMethod' => 'S256',
                'state' => ['domain' => $this->domain],
            ]
        );
    }

    /**
     * Return the authorization URL.
     *
     * @return string the authentication URL
     */
    public function getAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl(
            [
                'state' => json_encode(['domain' => $this->domain['host']]),
            ]
        );

        $pkceCode = $this->provider->getPkceCode();

        // Store a transient with the PKCE code that we need in order to
        // exchange the returned code for a token after authorization.
        set_transient(self::PKCE_TRANSIENT_NAME, $pkceCode, HOUR_IN_SECONDS);

        return $url;
    }

    /**
     * Requests the access token and refresh token based on the passed code.
     *
     * @param string $code the code to send
     *
     * @return array the requested tokens
     */
    public function requestTokens($code)
    {
        $pkceCode = get_transient(self::PKCE_TRANSIENT_NAME);

        if ($pkceCode) {
            $this->provider->setPkceCode($pkceCode);
        }

        try {
            $response = $this->provider
                ->getAccessToken(
                    'authorization_code',
                    [
                        'code' => $code,
                    ]
                );

            return $this->storeToken($response);
        } catch (Exception $exception) {
            //Error
            return [];
        }
    }

    /**
     * Performs an authenticated GET request to the desired URL.
     *
     * @param string $url     the URL to send the request to
     * @param array  $options the options to pass along to the request
     *
     * @return array the parsed API response
     */
    public function get($url, $options = [])
    {
        return $this->doRequest('GET', $url, $options);
    }

    /**
     * Performs an authenticated POST request to the desired URL.
     *
     * @param string $url     the URL to send the request to
     * @param mixed  $body    the data to send along in the request's body
     * @param array  $options the options to pass along to the request
     *
     * @return array the parsed API response
     */
    public function post($url, $body, $options = [])
    {
        $options['body'] = $body;
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Accept'] = 'application/json';

        return $this->doRequest('POST', $url, $options);
    }

    /**
     * Performs an authenticated DELETE request to the desired URL.
     *
     * @param string $url     the URL to send the request to
     * @param array  $options the options to pass along to the request
     *
     * @return array the parsed API response
     */
    public function delete($url, $options = [])
    {
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Accept'] = 'application/json';

        return $this->doRequest('DELETE', $url, $options);
    }

    /**
     * Determines whether tokens are set.
     *
     * @return bool whether tokens are set
     */
    public function hasTokens()
    {
        return !empty($this->tokens);
    }

    /**
     * Determines whether the tokens have expired.
     *
     * @return bool whether the tokens have expired
     */
    public function hasExpiredTokens()
    {
        return $this->tokens->hasExpired();
    }

    /**
     * Determines whether there are valid tokens available.
     *
     * @return bool whether there are valid tokens
     */
    public function hasValidTokens()
    {
        return $this->hasTokens() && false === $this->hasExpiredTokens();
    }

    /**
     * Gets the stored tokens and refreshes them if they've expired.
     *
     * @return AccessToken|array the stored tokens
     */
    public function getTokens()
    {
        if (!$this->hasTokens()) {
            return [];
        }

        if ($this->tokens->hasExpired()) {
            $this->tokens = $this->refreshTokens($this->tokens);
        }

        return $this->tokens;
    }

    /**
     * Stores the passed token.
     *
     * @param AccessToken $token the token to store
     *
     * @return AccessToken|array the stored token
     */
    public function storeToken(AccessToken $token)
    {
        $saved = update_option(self::TOKEN_OPTION, $token);

        if (false === $saved) {
            // Error when saving

            return [];
        }

        return $token;
    }

    /**
     * Performs the specified request.
     *
     * @param string $method  the HTTP method to use
     * @param string $url     the URL to send the request to
     * @param array  $options the options to pass along to the request
     *
     * @return array the parsed API response
     */
    protected function doRequest($method, $url, array $options)
    {
        $token = $this->getTokens();

        // No token is set. Return an Unauthorized message.
        if (empty($token)) {
            return [
                'status' => 401,
                'message' => 'Unauthorized',
            ];
        }

        $defaults = [
            'headers' => $this->provider->getHeaders($token->getToken()),
        ];

        $options = array_merge_recursive($defaults, $options);
        $url = self::BASE_URI . $url;

        if (array_key_exists('params', $options)) {
            $url .= '?' . http_build_query($options['params']);
            unset($options['params']);
        }

        try {
            $request = $this->provider->getAuthenticatedRequest($method, $url, null, $options);

            return $this->provider->getParsedResponse($request);
        } catch (IdentityProviderException $e) {
            return [
                'status' => $e->getCode(),
                'message' => $e->getResponseBody(),
            ];
        }
    }

    /**
     * Refreshes the outdated tokens.
     *
     * @param AccessToken $tokens the outdated tokens
     *
     * @return array the refreshed tokens
     */
    public function refreshTokens(AccessToken $tokens)
    {
        try {
            $response = $this->provider->getAccessToken(
                'refresh_token',
                [
                    'refresh_token' => $tokens->getRefreshToken(),
                ]
            );

            return $this->storeToken($response);
        } catch (Exception $exception) {
            // Error.

            return [];
        }
    }

    /**
     * Returns all data used by the WordPress dashboard.
     *
     * @param int    $websiteId the website ID to collect the data for
     * @param string $startDate the start date
     * @param string $endDate   The end date
     *
     * @return array the dashboard data
     */
    public function getDashboardData($websiteId, $startDate, $endDate)
    {
        return $this->post('websites/' . $websiteId . '/ranking-summary',
            json_encode([
                'start_at' => $startDate,
                'end_at' => $endDate,
            ])
        );
    }

    /**
     * Returns ranking history for one or more specific keywords.
     *
     * @param int    $websiteId  the website ID to get the ranking history for
     * @param array  $keywordIds the keyword IDs to get the history for
     * @param string $startDate  the start date
     * @param string $endDate    the end date
     *
     * @return array the ranking history
     */
    public function getRankingHistory($websiteId, array $keywordIds, $startDate, $endDate)
    {
        return $this->post('websites/' . $websiteId . '/ranking-history',
            json_encode([
                'keyword_ids' => $keywordIds,
                'start_at' => $startDate,
                'end_at' => $endDate,
            ])
        );
    }

    /**
     * Returns all currently available search engines.
     *
     * @return array the available search engines
     */
    public function getSearchEngines()
    {
        return $this->get('search-engines');
    }

    /**
     * Returns information about the authenticated account.
     *
     * @return array the account information
     */
    public function getAccount()
    {
        return $this->get('account');
    }

    /**
     * Returns all account websites.
     *
     * @return array the websites
     */
    public function getWebsites()
    {
        return $this->get('websites');
    }

    /**
     * Returns all keywords related to the website.
     *
     * @param int    $websiteId the website ID to get the keywords for
     * @param string $startDate the start date
     * @param string $endDate   the end date
     *
     * @return array the keywords
     */
    public function getKeywords($websiteId, $startDate, $endDate)
    {
        return $this->get(
            'websites/' . $websiteId . '/keywords',
            [
                'params' => [
                    'start_at' => $startDate,
                    'end_at' => $endDate,
                    'include_ranking' => 'true',
                ],
            ]);
    }

    /**
     * Creates a new keyword for a domain.
     *
     * @param int   $websiteId the website ID to create the keyword for
     * @param array $keyword   the keyword to create
     *
     * @return array the response
     */
    public function createKeyword($websiteId, $keyword)
    {
        return $this->post('websites/' . $websiteId . '/keywords/bulk',
            json_encode([['keyword' => $keyword, 'groups' => []]])
        );
    }

    /**
     * Deletes keywords from a domain.
     *
     * @param int   $websiteId  the website ID to delete keywords for
     * @param array $keywordIds the keyword ID(s) to delete
     *
     * @return array the response
     */
    public function deleteKeywords($websiteId, array $keywordIds)
    {
        return $this->delete('websites/' . $websiteId . '/keywords/bulk', [
            'body' => json_encode(['keyword_ids' => $keywordIds]),
        ]);
    }

    /**
     * Casts an object to a class
     *
     * @param int   $class  the target class
     * @param array $object the object to be casted
     *
     * @return object the casted object
     */
    private function cast_to_class($class, $object)
    {
        return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
    }
}
