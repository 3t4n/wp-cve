<?php

class Wadm_Feed_Abstract
{
    /**
     * @var array $_urlParameters
     */
    protected $_urlParameters = array();

    /**
     * @var array $_requestParameters Additional (optional) GET request parameters
     */
    protected $_requestParameters = [];

    /**
     * @var $_rawFeed
     */
    protected $_rawFeed = null;

    /**
     * @var bool $_cacheable Indicate whether feed is cacheable or not.
     */
    protected $_cacheable = true;

    /**
     * @var array $globalParameters Globally available parameters with their supported values
     */
    public static $globalParameters = [
        'medium_id' => [
            1, 2, 3, 4, 5, 8, 11, 12, 13, 15, 16, 17,
        ],
        'size' => [
            'small',
            'medium',
            'large',
            'xlarge',
        ],
        'language_code' => [
            'nl',
            'de',
            'fr',
        ],
        'locale' => [
            'nl_NL',
            'de_DE',
            'fr_FR',
            'de_AT',
            'de_CH',
            'fr_CH',
            'en_GB',
        ],
    ];

    /**
     * URL from which to fetch feeds from
     */
    const BASE_URL = 'https://www.werkaandemuur.nl/api/';


    /**
     * Fetch the actual feed. Constructs url from urlparts, and prepares authentication.
     *
     * Sends a cookie with PHPSESSID to enable the application to create an actual
     * session when authenticated.
     */
    protected function _fetch()
    {
        // Request cache available?
        if ($this->_rawFeed)
            return $this->_rawFeed;

        // Try to fetch from cache
        $result = get_transient($this->_getCacheKey());

        if ($this->_cacheable && Wadm::CACHE_ENABLED && $result !== false)
            return $result;

        // Keep file_get_contents as a backwards compatible option, but prefer to perform requests through cURL
        if (!extension_loaded('curl')) {
@           $result = file_get_contents($this->_constructUrl(), false, $this->_getStreamContext());

            preg_match('/HTTP\/\S*\s(\d{3})/', $http_response_header[0], $matches);
            $responseCode = (int)$matches[1];
        }
        else
        {
            $apiKey = get_option('wadm_api_key');
            $artistId = get_option('wadm_artist_id');

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $this->_constructUrl());
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_FAILONERROR, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_getHeaders());
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $artistId . ':' . $apiKey);

            $result = curl_exec($curl);
            $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);
        }

        // Cache result for one hour; except HTTP 500 and 403; cache them for ten minutes
        if ($this->_cacheable && Wadm::CACHE_ENABLED)
        {
            $ttl = 60*60;

            if (in_array($responseCode, [403, 500], true))
                $ttl = 60*10;

            set_transient($this->_getCacheKey(), $result, $ttl);
        }

        if ($result === false)
        {
            // Assume that the last error is from file_get_contents, throw this in an exception (to be ignored in normal daily use)
            $error = error_get_last();

            throw new Exception($error['message']);
        }

        // Test if we've got a valid JSON response
        if (!$this->_isJson($result))
            throw new Exception('Result is not a valid JSON response.');

        $parsedResult = json_decode($result);

        // Check for errors
        if ($parsedResult->status != 'success')
            throw new Exception($parsedResult->message);

        // Store JSON feed for future use in this request
        $this->_rawFeed = $result;

        return $result;
    }

    /**
     * Create stream context for api request
     */
    protected function _getStreamContext()
    {
        $artistId = get_option('wadm_artist_id');
        $apiKey = get_option('wadm_api_key');

        $headers = $this->_getHeaders();
        $headers[] = "Authorization: Basic " . base64_encode($artistId . ':' . $apiKey);

        $options = array('http' =>
            array(
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
                'ignore_errors' => true,
                'timeout' => 10,
            ),
        );

        return stream_context_create($options);
    }

    /**
     * Get headers shared between cURL and file_get_contents
     *
     * @return array
     */
    protected function _getHeaders()
    {
        $artistId = get_option('wadm_artist_id');
        $apiKey = get_option('wadm_api_key');

        return [
            "Cookie: PHPSESSID=" . md5($apiKey . $artistId . date('Y-m-d')),
            "User-Agent: WadM Wordpress plugin/" . Wadm::VERSION . " (Wordpress " . get_bloginfo('version') . (extension_loaded('curl') ? ", cURL" : "") . ")",
            "Referer: " . get_site_url(),
        ];
    }

    /*
     * Simple method to check if supplied response is JSON. Note that this method doesn't
     * cover all possible JSON results, but does cover everything which will be returned by the
     * wadm api.
     */
    protected function _isJson($string)
    {
        if (substr($string, 0, 1) != '{')
            return false;

        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Create a human readable cachekey for transient storage. Should be 45 chars or less
     */
    protected function _getCacheKey()
    {
        $cacheKey = 'wadm::feed::' . implode('::', $this->_urlParameters) . '::' . implode('::', $this->_requestParameters);

        if (strlen($cacheKey) > 45)
            return md5($cacheKey);

        return $cacheKey;
    }

    /**
     * Add a url parameter. Overwrites existing parameters with the same name.
     */
    public function addUrlParameter($name, $value)
    {
        $this->_urlParameters[$name] = $value;
    }

    /**
     * Return feed as raw json
     */
    public function getAsJson()
    {
        return $this->_fetch();
    }

    /**
     * Return feed as raw data
     */
    public function getData()
    {
        $result = json_decode($this->_fetch());

        return $result->data;
    }

    /**
     * Wrap all HTML output in some sort of namespace for styling purposes
     *
     * @param $html
     * @return string
     */
    public function getHtml($html)
    {
        return sprintf('<div id="wadm">%s</div>', $html);
    }

    /**
     * Return the constructed feed url
     */
    public function getUrl()
    {
        return $this->_constructUrl();
    }

    /**
     * Return HTTPS image url when ssl is enabled
     *
     * @param $artwork
     * @param $imageSize
     * @return mixed
     */
    public function getImageUrl($artwork, $imageSize)
    {
        return $artwork->imagesHttps->$imageSize;
    }

    /**
     * Make feed uncacheable. Useful for connection and / or authentication tests
     */
    public function setNotCacheable()
    {
        $this->_cacheable = false;
    }

    /**
     * Set request parameters. Accepts an array with unfiltered stuff, and filters
     * parameters based on defined global parameters and possible options.
     *
     * @param array $parameters
     */
    public function setRequestParameters(array $parameters)
    {
        foreach ($parameters as $name => $value)
        {
            if (!isset(self::$globalParameters[$name]))
                continue;

            if (!in_array($value, self::$globalParameters[$name]))
                continue;

            // Parameters need to be camelcased.
            $name = lcfirst(str_replace('_', '', ucwords($name, '_')));

            $this->_requestParameters[$name] = $value;
        }
    }

    /**
     * Construct feed url from url parameters. Note that the parameter order is based on
     * array order
     */
    protected function _constructUrl()
    {
        $path = self::BASE_URL . implode('/', $this->_urlParameters);
        $query = http_build_query($this->_requestParameters);

        return $path . ($query ? '?' . $query : '');
    }
}