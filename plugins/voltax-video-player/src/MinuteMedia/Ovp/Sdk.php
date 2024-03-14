<?php

namespace MinuteMedia\Ovp;

use MinuteMedia\Ovp\Constants;

class Sdk
{

    /**
     * Global Headers
     *
     * @var array
     */
    protected $headers = array(
        'content-type' => '',
        'x-sdk-version' => Constants::PLUGIN_PACKAGE_NAME . ':' . Constants::PLUGIN_VERSION,
        'accept-encoding' => 'gzip',
    );

    /**
     * API Endpoint URL
     *
     * @var string
     */
    protected $endpoint = Constants::ENDPOINT_VIDEO_RECEIVER;

    /**
     * API Endpoint URL for Players
     *
     * @var string
     */
    protected $endpointForPlayers = Constants::ENDPOINT_VIDEO_PLAYERS;

    /**
     * Is Self Signed Certificates Allowed?
     *
     * @var bool
     */
    protected $selfSigned = false;

    /**
     * API Client ID
     *
     * @var string
     */
    protected $clientId = '';

    /**
     * API Client Secret
     *
     * @var string
     */
    protected $clientSecret = '';

    /**
     * API Tenant ID
     *
     * @var string
     */
    protected $tenant = '';

    /**
     * API Property ID
     *
     * @var string
     */
    protected $property = '';

    /**
     * API Access Token
     *
     * @var string
     */
    protected $accessToken = '';

    /**
     * API Players Access Token
     *
     * @var string
     */
    protected $playersAccessToken = '';

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $tenant
     * @param string $property
     */
    public function __construct($clientId, $clientSecret, $tenant, $property)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tenant = $tenant;
        $this->property = $property;
    }

    /**
     * Should HTTP client allow self signed requests?
     *
     * @param bool $status
     * @return $this
     */
    public function setSelfSigned($status = true)
    {
        $this->selfSigned = $status;
        return $this;
    }

    /**
     * Get API Access Token
     */
    public function getAccessToken()
    {
        if (empty($this->accessToken)) {
            $response = $this->authenticate();
            if (isset($response['access_token'])) {
                $this->accessToken = $response['access_token'];
            } else {
                $errMsg = "";
                if (isset($response['error'])) {
                    $errMsg .= "<b>{$response['error']}: </b>{$response['error_description']}";
                }
                if (isset($response['error_description'])) {
                    $errMsg .= $response['error_description'];
                }
                wp_shortlink_wp_head($errMsg);
            }
        }

        return $this->accessToken;
    }

    /**
     * Get API Access Token for Players
     */
    public function getPlayersAccessToken()
    {
        if (empty($this->playersAccessToken)) {
            $response = $this->authenticate('players');
            if (isset($response['access_token'])) {
                $this->playersAccessToken = $response['access_token'];
            } else {
                $errMsg = "";
                if (isset($response['error'])) {
                    $errMsg .= "<b>{$response['error']}: </b>{$response['error_description']}";
                }
                if (isset($response['error_description'])) {
                    $errMsg .= $response['error_description'];
                }
                wp_shortlink_wp_head($errMsg);
            }
        }

        return $this->playersAccessToken;
    }

    /**
     * Set API Access Token
     *
     * @param string $accessToken
     * @return Sdk
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Set API Access Token for Players
     *
     * @param string $playersAccessToken
     * @return Sdk
     */
    public function setPlayersAccessToken($playersAccessToken)
    {
        $this->playersAccessToken = $playersAccessToken;

        return $this;
    }

    /**
     * Send an API authentication callto retrive an Access Token
     */
    public function authenticate($type = '')
    {
        // kludge for endpoint naming inconsistency
        if ($this->tenant == 'mmsport') {
            $this->endpoint = str_replace("-%s", "", $this->endpoint);
            $this->endpointForPlayers = str_replace("-%s", "", $this->endpointForPlayers);
        }
        $audience = sprintf($this->endpoint, $this->tenant);

        if ($type == 'players') {
            $audience = sprintf($this->endpointForPlayers, $this->tenant);
        }

        $response = $this->call(
            Constants::HTTP_METHOD_POST,
            Constants::ENDPOINT_OAUTH,
            Constants::ENDPOINT_OAUTH_PATH,
            array('content-type' => 'application/json'),
            array(
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'audience' => $audience,
                'grant_type' => 'client_credentials',
            ));
        return $response;
    }

    /**
     * Search videos with free text
     * @param $term
     * @param int $limit
     * @param int $offset
     * @return array|string
     */
    public function getVideos($term, $limit = 15, $offset = 0)
    {
        return $this->call(Constants::HTTP_METHOD_GET, $this->endpoint, '/v2.0/videos', array(
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ),
            array(
                'property' => $this->property,
                'qtext' => $term,
                'limit' => $limit,
                'offset' => $offset,
                //'tags_any' => $term,
                //'description' => $term,
            ));
    }

    /**
     * Search vidoes by id
     * @param $id
     * @return array|string
     */
    public function getVideoById($id)
    {
        return $this->call(Constants::HTTP_METHOD_GET, $this->endpoint, '/v1.0/videos/' . $id, array(
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ),
            array(
                'property' => $this->property,
            ));
    }

    /**
     * Search playlists by free text
     * @param $term
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getPlaylists($term = "", $offset = 0)
    {
        $data = $this->call(Constants::HTTP_METHOD_GET, $this->endpoint, '/v2.0/playlists', array(
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ),

            array(
                'property' => $this->property,
                'qtext' => $term,
                'offset' => $offset,
                'limit' => 200,
                //'tags_any' => $term,
                //'description' => $term,
            ));

        if (isset($data['data']['error'])) {
            return $data;
        }

        $data = (isset($data['data']) && isset($data['data']['playlists'])) ? $data['data']['playlists'] : array();

        return array(
            'data' => array_map(function ($item) {
                return array( // Normalize response to all requests
                    'payload_id' => $item['playlist_id'],
                    'title' => $item['title'],
                    'image' => '',
                    'type' => ($item['playlist_type'] == 1) ? 'Static Playlist' : 'Dynamic Playlist',
                    'items' => (isset($item['extra_data']) && isset($item['extra_data']['limit'])) ? $item['extra_data']['limit'] : 0,
                    'all' => $item,
                );
            }, $data)
        );
    }

    /**
     * Search players for this property
     * @param int $limit
     * @param int $offset
     * @return array|string
     */
    public function getPlayers($offset = 0)
    {
        return $this->call(Constants::HTTP_METHOD_GET, $this->endpointForPlayers, '/v1.0/players', array(
            'Authorization' => 'Bearer ' . $this->getPlayersAccessToken(),
        ),
            array(
                'property' => $this->property,
                'offset' => $offset,
            ));
    }

    public function getIabCategories()
    {
        return $this->call(Constants::HTTP_METHOD_GET, $this->endpoint, '/v2.0/iab-categories', array(
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ),
            array(
                'property' => $this->property,
                'sourceid' => 'f9aca131-f657-4738-9541-4cce8583ab93',
            ));
    }

    /**
     * Upload video
     * @return array|string
     */
    public function uploadVideo(
        $title,
        $description,
        array $tags,
        $video_provider,
        $creator,
        $property,
        $custom_params,
        $content_length,
        $content_md5,
        $file_extension,
        $opt_out_publish_external = true
    ) {
        $body = [
            "title" => $title,
            "description" => $description,
            "tags" => $tags,
            "video_provider" => $video_provider,
            "creator" => $creator,
            "create_date" => time(),
            "should_host" => true,
            "custom_params" => $custom_params,
            "property" => $property,
            "content_length" => $content_length,
            "content_md5" => $content_md5,
            "file_extension" => $file_extension,
            "opt_out_publish_external" => $opt_out_publish_external
        ];

        $apiCall = $this->call(
            Constants::HTTP_METHOD_POST,
            $this->endpoint,
            '/v2.0/upload/videos',
            [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'content-type' => 'application/json',
            ],
            $body
        );

        return $apiCall;
    }

    /**
     * Call
     *
     * Make an API call
     *
     * @param string $method
     * @param $endpoint
     * @param string $path
     * @param array $headers
     * @param array $params
     * @return array|string
     */
    public function call($method, $endpoint, $path = '', $headers = array(), array $params = array())
    {
        $endpoint = sprintf($endpoint, $this->tenant);
        $headers = array_merge($this->headers, $headers);
        $url = $endpoint . $path;
        $params['cachebuster'] = rand(1, 999999999);
        if ($method == Constants::HTTP_METHOD_GET && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $responseHeaders = array();

        switch ($headers['content-type']) {
            case 'application/json':
                $query = json_encode($params);
                break;
            case 'multipart/form-data':
                $query = $this->flatten($params);
                break;
            default:
                $query = http_build_query($params);
                break;
        }

//        unset($headers['Authorization']);

        $requestArgs = [
            'method' => $method,
            'headers' => $headers,
            'user-agent' => php_uname('s') . '-' . php_uname('r') . ':php-' . phpversion(),
            'compress' => true,
            'decompress' => true,
            'sslverify' => (!$this->selfSigned),
            'timeout' => 25,
        ];
        if ($method != Constants::HTTP_METHOD_GET) {
            $requestArgs['body'] = $query;
        }
        $response = wp_remote_request($url, $requestArgs);

//        \wpp_log([
//            'request' => $requestArgs + ['url' => $url],
//            'response' => $response
//        ]);

        if (is_wp_error($response)) {
            $responseBody = array(
                'data' => array(
                    'error' => __($response->get_error_message()),
                    'url' => $url,
                    'method' => $method,
                    'params' => $params
                )
            );
        } else {
            $responseBody = wp_remote_retrieve_body($response);
            $responseCode = $response['response']['code'];
            $responseHeaders = wp_remote_retrieve_headers($response);
            $responseType = (isset($responseHeaders['content-type'])) ? $responseHeaders['content-type'] : '';

            switch (substr($responseType, 0,
                (strpos($responseType, ';')) ? strpos($responseType, ';') : strlen($responseType))) {
                case 'application/json':
                    $responseBody = json_decode($responseBody, true);
                    break;
            }

            if (200 != $responseCode) {
                $error = "The video service returned an unexpected response code [{$responseCode}]";
                $responseBody = array(
                    'data' => array(
                        'error' => __($error),
                        'url' => $url,
                        'method' => $method,
                        'params' => $params
                    )
                );
            }
        }


        return $responseBody;
    }

    /**
     * Flatten params array to PHP multiple format
     *
     * @param array $data
     * @param string $prefix
     * @return array
     */
    protected function flatten(array $data, $prefix = '')
    {
        $output = array();
        foreach ($data as $key => $value) {
            $finalKey = $prefix ? "{$prefix}[{$key}]" : $key;
            if (is_array($value)) {
                $output += $this->flatten($value, $finalKey); // @todo: handle name collision here if needed
            } else {
                $output[$finalKey] = $value;
            }
        }
        return $output;
    }
}
