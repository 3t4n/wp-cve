<?php
/**
 * Request
 *
 * Abstracts the Client Request. Php 5.2 compatible
 *
 * Based upon the Symfony HttpFoundation Request Component
 * (c) Fabien Potencier <fabien@symfony.com> http://symfony.com/doc/current/contributing/code/license.html
 *
 * Copyright (c) Schuyler W Langdon.
 *
 * For the full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 */

class Reo_Classic_Request
{
    protected $defaultParams = array(
        'requestUri' => null,
        'basePath'   => null,
        'baseUrl'    => null,
        'pathInfo'   => null,
        'host'       => null,
        'method'     => null,
        'port'       => null,
        'secure'     => null,
    );

    protected $params;
    protected $parsed = false;

    //probably not needed at the app level
    public static $skipHostCheck = false;

    //proxy stuff
    const HEADER_CLIENT_IP    = 'client_ip';
    const HEADER_CLIENT_HOST  = 'client_host';
    const HEADER_CLIENT_PROTO = 'client_proto';
    const HEADER_CLIENT_PORT  = 'client_port';

    protected static $trustedProxies = array();

    /**
     * @var string[]
     */
    protected static $trustedHostPatterns = array();

    /**
     * @var string[]
     */
    protected static $trustedHosts = array();

    /**
     * Names for headers that can be trusted when
     * using trusted proxies.
     *
     * The default names are non-standard, but widely used
     * by popular reverse proxies (like Apache mod_proxy or Amazon EC2).
     */
    protected static $trustedHeaders = array(
        self::HEADER_CLIENT_IP    => 'X_FORWARDED_FOR',
        self::HEADER_CLIENT_HOST  => 'X_FORWARDED_HOST',
        self::HEADER_CLIENT_PROTO => 'X_FORWARDED_PROTO',
        self::HEADER_CLIENT_PORT  => 'X_FORWARDED_PORT',
    );

    protected static $httpMethodParameterOverride = false;

    /**
     * Custom parameters
     *
     * @var array
     *
     * @api
     */
    public $attributes;

    /**
     * Request body parameters ($_POST)
     *
     * @var array
     *
     * @api
     */
    public $request;

    /**
     * Query string parameters ($_GET)
     *
     * @var array
     *
     * @api
     */
    public $query;

    /**
     * Server and execution environment parameters ($_SERVER)
     *
     * @var array
     *
     * @api
     */
    public $server;

    /**
     * Uploaded files ($_FILES)
     *
     * @var array
     *
     * @api
     */
    public $files;

    /**
     * Cookies ($_COOKIE)
     *
     * @var array
     *
     * @api
     */
    public $cookies;

    /**
     * Headers (taken from the $_SERVER)
     *
     * @var array
     *
     * @api
     */
    public $headers;

     /**
     * @var string
     */
    protected $content;

    /**
     * @var array
     */
    protected static $formats;

     /**
     * Constructor.
     *
     * @param array  $query      The GET parameters
     * @param array  $request    The POST parameters
     * @param array  $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array  $cookies    The COOKIE parameters
     * @param array  $files      The FILES parameters
     * @param array  $server     The SERVER parameters
     * @param string $content    The raw body data
     *
     * @api
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Sets the parameters for this request.
     *
     * This method also re-initializes all properties.
     *
     * @param array  $query      The GET parameters
     * @param array  $request    The POST parameters
     * @param array  $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array  $cookies    The COOKIE parameters
     * @param array  $files      The FILES parameters
     * @param array  $server     The SERVER parameters
     * @param string $content    The raw body data
     *
     * @api
     */
    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->request = $request;
        $this->query = $query;
        //@note this won't be used
        $this->attributes = $attributes;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;
        $this->headers = $this->setHeaders();
        $this->content = $content;
        $this->params = $this->defaultParams;
        unset($this->parsed);
        //unset($this->parsed, $this->headers);
    }

    public static function createFromGlobals()
    {
        return new self($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * Creates a Request based on a given URI and configuration.
     *
     * The information contained in the URI always take precedence
     * over the other information (server and parameters).
     *
     * @param string $uri        The URI
     * @param string $method     The HTTP method
     * @param array  $parameters The query (GET) or request (POST) parameters
     * @param array  $cookies    The request cookies ($_COOKIE)
     * @param array  $files      The request files ($_FILES)
     * @param array  $server     The server parameters ($_SERVER)
     * @param string $content    The raw body data
     *
     * @return Request A Request instance
     *
     * @api
     */
    public static function create($uri, $method = 'GET', array $parameters = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $server = $server + array(
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => 80,
            'HTTP_HOST' => 'localhost',
            'HTTP_USER_AGENT' => 'Reo/1.X',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
            'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'REMOTE_ADDR' => '127.0.0.1',
            'SCRIPT_NAME' => '',
            'SCRIPT_FILENAME' => '',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_TIME' => time(),
        );

        $server['PATH_INFO'] = '';
        $server['REQUEST_METHOD'] = strtoupper($method);

        $components = parse_url($uri);
        if (isset($components['host'])) {
            $server['SERVER_NAME'] = $components['host'];
            $server['HTTP_HOST'] = $components['host'];
        }

        if (isset($components['scheme'])) {
            if ('https' === $components['scheme']) {
                $server['HTTPS'] = 'on';
                $server['SERVER_PORT'] = 443;
            } else {
                unset($server['HTTPS']);
                $server['SERVER_PORT'] = 80;
            }
        }

        if (isset($components['port'])) {
            $server['SERVER_PORT'] = $components['port'];
            $server['HTTP_HOST'] = $server['HTTP_HOST'].':'.$components['port'];
        }

        if (isset($components['user'])) {
            $server['PHP_AUTH_USER'] = $components['user'];
        }

        if (isset($components['pass'])) {
            $server['PHP_AUTH_PW'] = $components['pass'];
        }

        if (!isset($components['path'])) {
            $components['path'] = '/';
        }

        switch (strtoupper($method)) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                if (!isset($server['CONTENT_TYPE'])) {
                    $server['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
                }
                // no break
            case 'PATCH':
                $request = $parameters;
                $query = array();
                break;
            default:
                $request = array();
                $query = $parameters;
                break;
        }

        $queryString = '';
        if (isset($components['query'])) {
            parse_str(html_entity_decode($components['query']), $qs);

            if ($query) {
                $query = $query + $qs;
                $queryString = http_build_query($query, '', '&');
            } else {
                $query = $qs;
                $queryString = $components['query'];
            }
        } elseif ($query) {
            $queryString = http_build_query($query, '', '&');
        }

        $server['REQUEST_URI'] = $components['path'].('' !== $queryString ? '?'.$queryString : '');
        $server['QUERY_STRING'] = $queryString;

        return new self($query, $request, array(), $cookies, $files, $server, $content);
    }

    /**
     * Sets a list of trusted proxies.
     *
     * You should only list the reverse proxies that you manage directly.
     *
     * @param array $proxies A list of trusted proxies
     *
     * @api
     */
    public static function setTrustedProxies(array $proxies)
    {
        self::$trustedProxies = $proxies;
    }

    /**
     * Gets the list of trusted proxies.
     *
     * @return array An array of trusted proxies.
     */
    public static function getTrustedProxies()
    {
        return self::$trustedProxies;
    }

    /**
     * Sets a list of trusted host patterns.
     *
     * You should only list the hosts you manage using regexs.
     *
     * @param array $hostPatterns A list of trusted host patterns
     */
    public static function setTrustedHosts(array $hostPatterns)
    {
        self::$trustedHostPatterns = array_map('Reo_Classic_Request::filterHostPatterns', $hostPatterns);
        // we need to reset trusted hosts on trusted host patterns change
        self::$trustedHosts = array();
    }

    /**
     * php 5.2 compatible callable for setTrustedHosts
     */ 
    public static function filterHostPatterns($hostPattern) {
        return sprintf('{%s}i', str_replace('}', '\\}', $hostPattern));
    }

    /**
     * Gets the list of trusted host patterns.
     *
     * @return array An array of trusted host patterns.
     */
    public static function getTrustedHosts()
    {
        return self::$trustedHostPatterns;
    }

    /**
     * Sets the name for trusted headers.
     *
     * The following header keys are supported:
     *
     *  * Request::HEADER_CLIENT_IP:    defaults to X-Forwarded-For   (see getClientIp())
     *  * Request::HEADER_CLIENT_HOST:  defaults to X-Forwarded-Host  (see getClientHost())
     *  * Request::HEADER_CLIENT_PORT:  defaults to X-Forwarded-Port  (see getClientPort())
     *  * Request::HEADER_CLIENT_PROTO: defaults to X-Forwarded-Proto (see getScheme() and isSecure())
     *
     * Setting an empty value allows to disable the trusted header for the given key.
     *
     * @param string $key   The header key
     * @param string $value The header name
     *
     * @throws \InvalidArgumentException
     */
    public static function setTrustedHeaderName($key, $value)
    {
        if (!array_key_exists($key, self::$trustedHeaders)) {
            throw new InvalidArgumentException(sprintf('Unable to set the trusted header name for key "%s".', $key));
        }

        self::$trustedHeaders[$key] = $value;
    }

    /**
     * Gets the trusted proxy header name.
     *
     * @param string $key The header key
     *
     * @return string The header name
     *
     * @throws \InvalidArgumentException
     */
    public static function getTrustedHeaderName($key)
    {
        if (!array_key_exists($key, self::$trustedHeaders)) {
            throw new InvalidArgumentException(sprintf('Unable to get the trusted header name for key "%s".', $key));
        }

        return self::$trustedHeaders[$key];
    }

    /**
     * Normalizes a query string.
     *
     * It builds a normalized query string, where keys/value pairs are alphabetized,
     * have consistent escaping and unneeded delimiters are removed.
     *
     * @param string $qs Query string
     *
     * @return string A normalized query string for the Request
     */
    public static function normalizeQueryString($qs)
    {
        if ('' == $qs) {
            return '';
        }

        $parts = array();
        $order = array();

        foreach (explode('&', $qs) as $param) {
            if ('' === $param || '=' === $param[0]) {
                // Ignore useless delimiters, e.g. "x=y&".
                // Also ignore pairs with empty key, even if there was a value, e.g. "=value", as such nameless values cannot be retrieved anyway.
                // PHP also does not include them when building _GET.
                continue;
            }

            $keyValuePair = explode('=', $param, 2);

            // GET parameters, that are submitted from a HTML form, encode spaces as "+" by default (as defined in enctype application/x-www-form-urlencoded).
            // PHP also converts "+" to spaces when filling the global _GET or when using the function parse_str. This is why we use urldecode and then normalize to
            // RFC 3986 with rawurlencode.
            $parts[] = isset($keyValuePair[1]) ?
                rawurlencode(urldecode($keyValuePair[0])).'='.rawurlencode(urldecode($keyValuePair[1])) :
                rawurlencode(urldecode($keyValuePair[0]));
            $order[] = urldecode($keyValuePair[0]);
        }

        array_multisort($order, SORT_ASC, $parts);

        return implode('&', $parts);
    }

    
    /**
     * Enables support for the _method request parameter to determine the intended HTTP method.
     *
     * Be warned that enabling this feature might lead to CSRF issues in your code.
     * Check that you are using CSRF tokens when required.
     *
     * The HTTP method can only be overridden when the real HTTP method is POST.
     */
    public static function enableHttpMethodParameterOverride()
    {
        self::$httpMethodParameterOverride = true;
    }

    /**
     * Checks whether support for the _method request parameter is enabled.
     *
     * @return bool    True when the _method request parameter is enabled, false otherwise
     */
    public static function getHttpMethodParameterOverride()
    {
        return self::$httpMethodParameterOverride;
    }

    /**
     * Returns the client IP addresses.
     *
     * In the returned array the most trusted IP address is first, and the
     * least trusted one last. The "real" client IP address is the last one,
     * but this is also the least trusted one. Trusted proxies are stripped.
     *
     * Use this method carefully; you should use getClientIp() instead.
     *
     * @return array The client IP addresses
     *
     * @see getClientIp()
     */
    public function getClientIps()
    {
        $ip = $this->server['REMOTE_ADDR'];

        if (!self::$trustedProxies) {
            return array($ip);
        }

        if (!self::$trustedHeaders[self::HEADER_CLIENT_IP] || null === ($headerIps = $this->getHeaders(self::$trustedHeaders[self::HEADER_CLIENT_IP]))) {
            return array($ip);
        }

        $clientIps = array_map('trim', explode(',', $headerIps));
        $clientIps[] = $ip; // Complete the IP chain with the IP the request actually came from

        $ip = $clientIps[0]; // Fallback to this when the client IP falls into the range of trusted proxies

        // Eliminate all IPs from the forwarded IP chain which are trusted proxies
        foreach ($clientIps as $key => $clientIp) {
            if (self::checkIp($clientIp, self::$trustedProxies)) {
                unset($clientIps[$key]);
            }
        }

        // Now the IP chain contains only untrusted proxies and the client IP
        return $clientIps ? array_reverse($clientIps) : array($ip);
    }

    /**
     * Returns the client IP address.
     *
     * This method can read the client IP address from the "X-Forwarded-For" header
     * when trusted proxies were set via "setTrustedProxies()". The "X-Forwarded-For"
     * header value is a comma+space separated list of IP addresses, the left-most
     * being the original client, and each successive proxy that passed the request
     * adding the IP address where it received the request from.
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-For",
     * ("Client-Ip" for instance), configure it via "setTrustedHeaderName()" with
     * the "client-ip" key.
     *
     * @return string The client IP address
     *
     * @see getClientIps()
     * @see http://en.wikipedia.org/wiki/X-Forwarded-For
     *
     * @api
     */
    public function getClientIp()
    {
        $ipAddresses = $this->getClientIps();

        return $ipAddresses[0];
    }

    /**
     * Returns current script name.
     *
     * @return string
     *
     * @api
     */
    public function getScriptName()
    {
        return isset($this->server['SCRIPT_NAME']) ? $this->server['SCRIPT_NAME'] : (isset($this->server['ORIG_SCRIPT_NAME']) ? $this->server['ORIG_SCRIPT_NAME'] : '');
    }

    /**
     * Returns the path being requested relative to the executed script.
     *
     * The path info always starts with a /.
     *
     * Suppose this request is instantiated from /mysite on localhost:
     *
     *  * http://localhost/mysite              returns an empty string
     *  * http://localhost/mysite/about        returns '/about'
     *  * http://localhost/mysite/enco%20ded   returns '/enco%20ded'
     *  * http://localhost/mysite/about?var=1  returns '/about'
     *
     * @return string The raw path (i.e. not urldecoded)
     *
     * @api
     */
    public function getPathInfo()
    {
        if (null !== $this->params['pathInfo']) {
            return $this->params['pathInfo'];
        }

        $baseUrl = $this->getBaseUrl();

        if (null === ($requestUri = $this->getRequestUri())) {
            return '/';
        }

        $pathInfo = '/';

        // Remove the query string from REQUEST_URI
        if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        if (null !== $baseUrl && false === $pathInfo = substr($requestUri, strlen($baseUrl))) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        } elseif (null === $baseUrl) {
            return $requestUri;
        }

        return (string) $pathInfo;
        
        return $this->params['pathInfo'] = $this->getParsed('path');
    }

    /**
     * Returns the root path from which this request is executed.
     *
     * Suppose that an index.php file instantiates this request object:
     *
     *  * http://localhost/index.php         returns an empty string
     *  * http://localhost/index.php/page    returns an empty string
     *  * http://localhost/web/index.php     returns '/web'
     *  * http://localhost/we%20b/index.php  returns '/we%20b'
     *
     * @return string The raw path (i.e. not urldecoded)
     *
     * @api
     */
    public function getBasePath()
    {
        if (null !== $this->params['basePath']) {
            return $this->params['basePath'];
        }

        $filename = basename(isset($this->server['SCRIPT_FILENAME']) ? $this->server['SCRIPT_FILENAME'] : null);
        $baseUrl = $this->getBaseUrl();
        if (empty($baseUrl)) {
            return $this->params['basePath'] = '';
        }

        if (basename($baseUrl) === $filename) {
            $this->params['basePath'] = dirname($baseUrl);
        } else {
            $this->params['basePath'] = $baseUrl;
        }

        if ('\\' === DIRECTORY_SEPARATOR) {
            $this->params['basePath'] = str_replace('\\', '/', $this->params['basePath']);
        }

        return $this->params['basePath'] = rtrim($this->params['basePath'], '/');
    }

    /**
     * Returns the root url from which this request is executed.
     *
     * The base URL never ends with a /.
     *
     * This is similar to getBasePath(), except that it also includes the
     * script filename (e.g. index.php) if one exists.
     *
     * @return string The raw url (i.e. not urldecoded)
     *
     * @api
     */
    public function getBaseUrl()
    {
        if (null !== $this->params['baseUrl']) {
            return $this->params['baseUrl'];
        }
        $filename = basename(isset($this->server['SCRIPT_FILENAME']) ? $this->server['SCRIPT_FILENAME'] : null);
        //$filename = basename($this->getServer('SCRIPT_FILENAME'));

        if (basename(($isset = isset($this->server['SCRIPT_NAME'])) ? $this->server['SCRIPT_NAME'] : null) === $filename) {
            $baseUrl = $isset ? $this->server['SCRIPT_NAME'] : null;
        } elseif (basename(isset($this->server['PHP_SELF']) ? $this->server['PHP_SELF'] : null) === $filename) {
            $baseUrl = $this->server['PHP_SELF'];
        } elseif (basename(isset($this->server['ORIG_SCRIPT_NAME']) ? $this->server['ORIG_SCRIPT_NAME'] : null) === $filename) {
            $baseUrl = $this->server['ORIG_SCRIPT_NAME']; // 1and1 shared hosting compatibility
        } else {
            // Backtrack up the script_filename to find the portion matching
            // php_self
            $path    = isset($this->server['PHP_SELF']) ? $this->server['PHP_SELF'] : '';
            $file    = isset($this->server['SCRIPT_FILENAME']) ? $this->server['SCRIPT_FILENAME'] : '';
            $segs    = explode('/', trim($file, '/'));
            $segs    = array_reverse($segs);
            $index   = 0;
            $last    = count($segs);
            $baseUrl = '';
            do {
                $seg     = $segs[$index];
                $baseUrl = '/'.$seg.$baseUrl;
                ++$index;
            } while (($last > $index) && (false !== ($pos = strpos($path, $baseUrl))) && (0 != $pos));
        }

        // Does the baseUrl have anything in common with the request_uri?
        $requestUri = $this->getRequestUri();

        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, $baseUrl)) {
            // full $baseUrl matches
            return $prefix;
        }

        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, dirname($baseUrl))) {
            // directory portion of $baseUrl matches
            return rtrim($prefix, '/');
        }

        $truncatedRequestUri = $requestUri;
        if (($pos = strpos($requestUri, '?')) !== false) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);
        if (empty($basename) || !strpos(rawurldecode($truncatedRequestUri), $basename)) {
            // no match whatsoever; set it blank
            return '';
        }

        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of baseUrl. $pos !== 0 makes sure it is not matching a value
        // from PATH_INFO or QUERY_STRING
        if ((strlen($requestUri) >= strlen($baseUrl)) && ((false !== ($pos = strpos($requestUri, $baseUrl))) && ($pos !== 0))) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return $this->params['baseUrl'] = rtrim($baseUrl, '/');
    }

    /**
     * Gets the request's scheme.
     *
     * @return string
     *
     * @api
     */
    public function getScheme()
    {
        return isset($this->params['scheme']) ?  $this->params['scheme'] : ($this->params['scheme'] = $this->isSecure() ? 'https' : 'http');
    }

    /**
     * Returns the port on which the request is made.
     *
     * This method can read the client port from the "X-Forwarded-Port" header
     * when trusted proxies were set via "setTrustedProxies()".
     *
     * The "X-Forwarded-Port" header must contain the client port.
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-Port",
     * configure it via "setTrustedHeaderName()" with the "client-port" key.
     *
     * @return string
     *
     * @api
     */
    public function getPort()
    {
        if (isset($this->params['port'])) {
            return $this->params['port'];
        }

        if (self::$trustedProxies) {
            if (self::$trustedHeaders[self::HEADER_CLIENT_PORT] && null !== ($port = $this->getHeaders(self::$trustedHeaders[self::HEADER_CLIENT_PORT]))) {
                return $this->params['port'] = (int)$port;
            }

            if (self::$trustedHeaders[self::HEADER_CLIENT_PROTO] && 'https' === ($this->getHeaders(self::$trustedHeaders[self::HEADER_CLIENT_PROTO]))) {
                return $this->params['port'] = 443;
            }
        }
        //@note unmodified, this fails it's own unit test, unless it's normalizing the host header
        if (null !== ($host = $this->getHeaders('HOST'))) {
            if (false !== ($pos = strrpos($host, ':'))) {
                if (ctype_digit($port = substr($host, $pos + 1))) {
                    return $this->params['port'] = (int)$port;
                }
            }
            return $this->params['port'] = ('https' === $this->getScheme()) ? 443 : 80;
        }

        return $this->params['port'] = isset($this->server['SERVER_PORT']) ? $this->server['SERVER_PORT'] : null;
    }

    /**
     * Returns the user.
     *
     * @return string|null
     */
    public function getUser()
    {
        return $this->getHeaders('PHP_AUTH_USER');
    }

    /**
     * Returns the password.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->getHeaders('PHP_AUTH_PW');
    }

    /**
     * Gets the user info.
     *
     * @return string A user name and, optionally, scheme-specific information about how to gain authorization to access the server
     */
    public function getUserInfo()
    {
        $userinfo = $this->getUser();

        $pass = $this->getPassword();
        if ('' != $pass) {
            $userinfo .= ":$pass";
        }

        return $userinfo;
    }

    /**
     * Returns the HTTP host being requested.
     *
     * The port name will be appended to the host if it's non-standard.
     *
     * @return string
     *
     * @api
     */
    public function getHttpHost()
    {
        $scheme = $this->getScheme();
        $port   = $this->getPort();

        if (('http' == $scheme && $port == 80) || ('https' == $scheme && $port == 443)) {
            return $this->getHost();
        }

        return $this->getHost().':'.$port;
    }

     /**
     * Returns the requested URI.
     *
     * @return string The raw URI (i.e. not urldecoded)
     *
     * @api
     */
    public function getRequestUri()
    {
        if (null !== $this->params['requestUri']) {
            // || ($this->skipHeaderCheck && ($this->params['requestUri'] = $this->server['REQUEST_URI']))) {
            return $this->params['requestUri'];
        }

        $requestUri = '';

        if (null !== $this->getHeaders('X_ORIGINAL_URL')) {
            // IIS with Microsoft Rewrite Module
            $requestUri = $this->getHeaders('X_ORIGINAL_URL');
            unset($this->headers['X_ORIGINAL_URL'], $this->server['HTTP_X_ORIGINAL_URL'], $this->server['UNENCODED_URL'], $this->server['IIS_WasUrlRewritten']);
        } elseif (null !== $this->getHeaders('X_REWRITE_URL')) {
            // IIS with ISAPI_Rewrite
            $requestUri = $this->getHeaders('X_REWRITE_URL');
            unset($this->headers['X_REWRITE_URL']);
        } elseif (isset($this->server['IIS_WasUrlRewritten']) && $this->server['IIS_WasUrlRewritten'] == '1' && $this->server['UNENCODED_URL'] != '') {
            // IIS7 with URL Rewrite: make sure we get the unencoded URL (double slash problem)
            $requestUri = $this->server['UNENCODED_URL'];
            unset($this->server['UNENCODED_URL'], $this->server['IIS_WasUrlRewritten']);
        } elseif (isset($this->server['REQUEST_URI'])) {
            $requestUri = $this->server['REQUEST_URI'];
            // HTTP proxy reqs setup request URI with scheme and host [and port] + the URL path, only use URL path
            $schemeAndHttpHost = $this->getSchemeAndHttpHost();
            if (strpos($requestUri, $schemeAndHttpHost) === 0) {
                $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            }
        } elseif (isset($this->server['ORIG_PATH_INFO'])) {
            // IIS 5.0, PHP as CGI
            $requestUri = $this->server['ORIG_PATH_INFO'];
            if (isset($this->server['QUERY_STRING']) && '' != $this->server['QUERY_STRING']) {
                $requestUri .= '?'.$this->server['QUERY_STRING'];
            }
            unset($this->server['ORIG_PATH_INFO']);
        }

        // normalize the request URI to ease creating sub-requests from this request
        $this->server['REQUEST_URI'] = $requestUri;

        return $this->params['requestUri'] = $requestUri;
    }

    /**
     * Gets the scheme and HTTP host.
     *
     * If the URL was called with basic authentication, the user
     * and the password are not added to the generated string.
     *
     * @return string The scheme and HTTP host
     */
    public function getSchemeAndHttpHost()
    {
        return $this->getScheme() . '://' . $this->getHttpHost();
    }

    /**
     * Generates a normalized URI for the Request.
     *
     * @return string A normalized URI for the Request
     *
     * @see getQueryString()
     *
     * @api
     */
    public function getUri()
    {
        if (null !== $qs = $this->getQueryString()) {
            $qs = '?' . $qs;
        }

        return $this->getSchemeAndHttpHost() . $this->getBaseUrl() . $this->getPathInfo() . $qs;
    }

     /**
     * Generates a normalized URI for the given path.
     *
     * @param string $path A path to use instead of the current one
     *
     * @return string The normalized URI for the path
     *
     * @api
     */
    public function getUriForPath($path)
    {
        return $this->getSchemeAndHttpHost().$this->getBaseUrl().$path;
    }

    /**
     * Gets the parsed query string
     *
     * @return string|null the query string for the Request
     *
     * @api
     */
    public function getQueryString()
    {
        if (array_key_exists('qs', $this->params)) {
            return $this->params['qs'];
        }

        //@note seems that query_string should be used rather than parsing the uri
        if (isset($this->server['QUERY_STRING'])) {
            // check for reverse proxy and remove url portion of query string
            if (isset($this->server['HTTP_X_ORIGINAL_URL']) && 0 === strpos($this->server['QUERY_STRING'], $path = $this->getPathInfo())) {
                $this->server['QUERY_STRING'] = substr($this->server['QUERY_STRING'], strlen($path));
            }
            //normalize
            if (strstr($this->server['QUERY_STRING'], '=') || strstr($this->server['QUERY_STRING'], '&')) {
                $this->params['qs'] = self::normalizeQueryString($this->server['QUERY_STRING']);
            } else {
                $this->params['qs'] = $this->server['QUERY_STRING'];
            }
        }
        return !isset($this->params['qs']) || '' === $this->params['qs'] ? $this->params['qs'] = null : $this->params['qs'];
        //$this->getParsed('query');@depricate
    }

    /**
     * Checks whether the request is secure or not.
     *
     * This method can read the client port from the "X-Forwarded-Proto" header
     * when trusted proxies were set via "setTrustedProxies()".
     *
     * The "X-Forwarded-Proto" header must contain the protocol: "https" or "http".
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-Proto"
     * ("SSL_HTTPS" for instance), configure it via "setTrustedHeaderName()" with
     * the "client-proto" key.
     *
     * @return Boolean
     *
     * @api
     */
    public function isSecure()
    {
        if (null !== $this->params['secure']) {
            return $this->params['secure'];
        }
        if (self::$trustedProxies && self::$trustedHeaders[self::HEADER_CLIENT_PROTO] && null !== ($proto = $this->getHeaders(self::$trustedHeaders[self::HEADER_CLIENT_PROTO]))) {
            return $this->params['secure'] = in_array(strtolower(current(explode(',', $proto))), array('https', 'on', 'ssl', '1'));
        }

        return $this->params['secure'] = isset($this->server['HTTPS']) && ('on' == strtolower($this->server['HTTPS']) || 1 == $this->server['HTTPS']);
    }

    /**
     * Returns the host name.
     *
     * This method can read the client port from the "X-Forwarded-Host" header
     * when trusted proxies were set via "setTrustedProxies()".
     *
     * The "X-Forwarded-Host" header must contain the client host name.
     *
     * If your reverse proxy uses a different header name than "X-Forwarded-Host",
     * configure it via "setTrustedHeaderName()" with the "client-host" key.
     *
     * @return string
     *
     * @throws \UnexpectedValueException when the host name is invalid
     *
     * @api
     */

    public function getHost()
    {
        if (isset($this->params['host'])) {
            return $this->params['host'];
        }

        if (self::$trustedProxies && self::$trustedHeaders[self::HEADER_CLIENT_HOST] && null !== ($host = $this->getHeaders(self::$trustedHeaders[self::HEADER_CLIENT_HOST]))) {
            $elements = explode(',', $host);
            $host = $elements[count($elements) - 1];
        } elseif (null === ($host = $this->getHeaders('HOST'))) {
            if (null === ($host = isset($this->server['SERVER_NAME']) ? $this->server['SERVER_NAME'] : null) && null === ($host = isset($this->server['SERVER_ADDR']) ? $this->server['SERVER_ADDR'] : null)) {
                $host = '';
            }
        }

        // trim and remove port number from host
        // host is lowercase as per RFC 952/2181
        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));

        // as the host can come from the user (HTTP_HOST and depending on the configuration, SERVER_NAME too can come from the user)
        // check that it does not contain forbidden characters (see RFC 952 and RFC 2181)
        if (!self::$skipHostCheck && '' !== $host && !preg_match('/^\[?(?:[a-zA-Z0-9-:\]_]+\.?)+$/', $host)) {
            throw new UnexpectedValueException(sprintf('Invalid Host "%s"', $host));
        }

        if (!self::$skipHostCheck && count(self::$trustedHostPatterns) > 0) {
            // to avoid host header injection attacks, you should provide a list of trusted host patterns

            if (in_array($host, self::$trustedHosts)) {
                return $this->params['host'] = $host;
            }

            foreach (self::$trustedHostPatterns as $pattern) {
                if (preg_match($pattern, $host)) {
                    self::$trustedHosts[] = $host;

                    return $this->params['host'] = $host;
                }
            }

            throw new UnexpectedValueException(sprintf('Untrusted Host "%s"', $host));
        }

        return $this->params['host'] = $host;
    }

    /**
     * Sets the request method.
     *
     * @param string $method
     *
     * @api
     */
    public function setMethod($method)
    {
        $this->params['method'] = null;
        $this->server['REQUEST_METHOD'] = strtoupper($method);
    }

    /**
     * Gets the request "intended" method.
     *
     * If the X-HTTP-Method-Override header is set, and if the method is a POST,
     * then it is used to determine the "real" intended HTTP method.
     *
     * The _method request parameter can also be used to determine the HTTP method,
     * but only if enableHttpMethodParameterOverride() has been called.
     *
     * The method is always an uppercased string.
     *
     * @return string The request method
     *
     * @api
     *
     * @see getRealMethod
     */
    public function getMethod()
    {
        if (null !== $this->params['method']) {
            return $this->params['method'];
        }

        return $this->params['method'] = !isset($this->server['REQUEST_METHOD']) ? 'GET' : strtoupper($this->server['REQUEST_METHOD']);

        if ('POST' === $this->method) {
            if ($method = $this->headers->get('X-HTTP-METHOD-OVERRIDE')) {
                $this->method = strtoupper($method);
            } elseif (self::$httpMethodParameterOverride) {
                $this->method = strtoupper($this->request->get('_method', $this->query->get('_method', 'POST')));
            }
        }

        return $this->method;
    }

     /**
     * Gets the "real" request method.
     *
     * @return string The request method
     *
     * @see getMethod
     */
    public function getRealMethod()
    {
        return strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
    }

    /**
     * clearParamCache
     *
     * clears the common params associated with a request
     * since a request doesn't change, the use case is for testing
     */
    public function clearParamCache()
    {
        $this->params = $this->defaultParams;
        return $this;
    }

/**
 * getServer
 *
 * When server is an array
 */
    public function getServer($key, $default = null)
    {
        return array_key_exists($key, $this->server) ? $this->server[$key] : $default;
    }

    /**
     * Returns the request body content.
     *
     * @param Boolean $asResource If true, a resource will be returned
     *
     * @return string|resource The request body content or a resource to read the body stream.
     *
     * @throws \LogicException
     */
    public function getContent($asResource = false)
    {
        if (false === $this->content || (true === $asResource && null !== $this->content)) {
            throw new LogicException('getContent() can only be called once when using the resource return type.');
        }

        if (true === $asResource) {
            $this->content = false;

            return fopen('php://input', 'rb');
        }

        if (null === $this->content) {
            $this->content = file_get_contents('php://input');
        }

        return $this->content;
    }

    /**
     * Checks if the request method is of specified type.
     *
     * @param string $method Uppercase request method (GET, POST etc).
     *
     * @return Boolean
     */
    public function isMethod($method)
    {
        return $this->getMethod() === strtoupper($method);
    }

    /**
     * Gets the mime type associated with the format.
     *
     * @param string $format The format
     *
     * @return string The associated mime type (null if not found)
     *
     * @api
     */
    public function getMimeType($format)
    {
        if (null === self::$formats) {
            self::initializeFormats();
        }

        return isset(self::$formats[$format]) ? self::$formats[$format][0] : null;
    }

    /**
     * Gets the format associated with the mime type.
     *
     * @param string $mimeType The associated mime type
     *
     * @return string|null The format (null if not found)
     *
     * @api
     */
    public function getFormat($mimeType)
    {
        if (false !== $pos = strpos($mimeType, ';')) {
            $mimeType = substr($mimeType, 0, $pos);
        }

        if (null === self::$formats) {
            self::initializeFormats();
        }

        foreach (self::$formats as $format => $mimeTypes) {
            if (in_array($mimeType, (array) $mimeTypes)) {
                return $format;
            }
        }
    }

    /**
     * Associates a format with mime types.
     *
     * @param string       $format    The format
     * @param string|array $mimeTypes The associated mime types (the preferred one must be the first as it will be used as the content type)
     *
     * @api
     */
    public function setFormat($format, $mimeTypes)
    {
        if (null === self::$formats) {
            self::initializeFormats();
        }

        self::$formats[$format] = is_array($mimeTypes) ? $mimeTypes : array($mimeTypes);
    }

    /**
     * Gets the format associated with the request.
     *
     * @return string|null The format (null if no content type is present)
     *
     * @api
     */
    public function getContentType()
    {
        return $this->getFormat($this->getHeaders('CONTENT_TYPE'));
    }

    /**
     * hasQueryString
     *
     * Check if the request has a query string, without parsing it
     */
    public function hasQueryString()
    {
        return null !== $this->getQueryString();
    }

    /**
     * Returns true if the request is a XMLHttpRequest.
     *
     * It works if your JavaScript library set an X-Requested-With HTTP header.
     * It is known to work with common JavaScript frameworks:
     * @link http://en.wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript
     *
     * @return bool    true if the request is an XMLHttpRequest, false otherwise
     *
     * @api
     */
    public function isXmlHttpRequest()
    {
        return 'XMLHttpRequest' === (isset($this->headers['X-Requested-With']) ? $this->headers['X-Requested-With'] : false);
    }

    public static function enableHostCheck()
    {
        self::$skipHostCheck = false;
    }

    public static function disableHostCheck()
    {
        self::$skipHostCheck = true;
    }

    protected static function initializeFormats()
    {
        self::$formats = array(
            'html' => array('text/html', 'application/xhtml+xml'),
            'txt'  => array('text/plain'),
            'js'   => array('application/javascript', 'application/x-javascript', 'text/javascript'),
            'css'  => array('text/css'),
            'json' => array('application/json', 'application/x-json'),
            'xml'  => array('text/xml', 'application/xml', 'application/x-xml'),
            'rdf'  => array('application/rdf+xml'),
            'atom' => array('application/atom+xml'),
            'rss'  => array('application/rss+xml'),
        );
    }

    /*
     * Returns the prefix as encoded in the string when the string starts with
     * the given prefix, false otherwise.
     *
     * @param string $string The urlencoded string
     * @param string $prefix The prefix not encoded
     *
     * @return string|false The prefix as it is encoded in $string, or false
     */
    private function getUrlencodedPrefix($string, $prefix)
    {
        if (0 !== strpos(rawurldecode($string), $prefix)) {
            return false;
        }

        $len = strlen($prefix);

        if (preg_match("#^(%[[:xdigit:]]{2}|.){{$len}}#", $string, $match)) {
            return $match[0];
        }

        return false;
    }

    public function getParsed($key)
    {
        if (!isset($this->parsed)) {
            $this->parsed = parse_url($this->getRequestUri());
            if ('' === $this->parsed['path']) {
                $this->parsed['path'] = '/';
            }
        }

        return empty($this->parsed[$key]) ? null : $this->parsed[$key];
    }

    /**
     * Gets the HTTP headers.
     *
     * @return array
     */
    public function getHeaders($key = null)
    {
        if (!isset($this->headers)) {
            $this->headers = $this->setHeaders();
        }
        if (isset($key)) {
            return isset($this->headers[$key]) ? $this->headers[$key] : null;
        }
        return $this->headers;
    }

    /**
     * Sets the HTTP headers.
     *
     * @return array
     */
    protected function setHeaders()
    {
        $headers = array();
        $contentHeaders = array('CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true);
        foreach ($this->server as $key => $value) {
            if (0 === strpos($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            }
            // CONTENT_* are not prefixed with HTTP_
            elseif (isset($contentHeaders[$key])) {
                $headers[$key] = $value;
            }
        }

        if (isset($this->server['PHP_AUTH_USER'])) {
            $headers['PHP_AUTH_USER'] = $this->server['PHP_AUTH_USER'];
            $headers['PHP_AUTH_PW'] = isset($this->server['PHP_AUTH_PW']) ? $this->server['PHP_AUTH_PW'] : '';
        } else {
            /*
             * php-cgi under Apache does not pass HTTP Basic user/pass to PHP by default
             * For this workaround to work, add these lines to your .htaccess file:
             * RewriteCond %{HTTP:Authorization} ^(.+)$
             * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
             *
             * A sample .htaccess file:
             * RewriteEngine On
             * RewriteCond %{HTTP:Authorization} ^(.+)$
             * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
             * RewriteCond %{REQUEST_FILENAME} !-f
             * RewriteRule ^(.*)$ app.php [QSA,L]
             */

            $authorizationHeader = null;
            if (isset($this->server['HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $this->server['HTTP_AUTHORIZATION'];
            } elseif (isset($this->server['REDIRECT_HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $this->server['REDIRECT_HTTP_AUTHORIZATION'];
            }

            if (null !== $authorizationHeader) {
                if (0 === stripos($authorizationHeader, 'basic ')) {
                    // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
                    $exploded = explode(':', base64_decode(substr($authorizationHeader, 6)), 2);
                    if (count($exploded) == 2) {
                        list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = $exploded;
                    }
                } elseif (empty($this->server['PHP_AUTH_DIGEST']) && (0 === stripos($authorizationHeader, 'digest '))) {
                    // In some circumstances PHP_AUTH_DIGEST needs to be set
                    $headers['PHP_AUTH_DIGEST'] = $authorizationHeader;
                    $this->server['PHP_AUTH_DIGEST'] = $authorizationHeader;
                }
            }
        }

        // PHP_AUTH_USER/PHP_AUTH_PW
        if (isset($headers['PHP_AUTH_USER'])) {
            $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
        } elseif (isset($headers['PHP_AUTH_DIGEST'])) {
            $headers['AUTHORIZATION'] = $headers['PHP_AUTH_DIGEST'];
        }

        return $headers;
    }

    //static methods from Symfony\Component\HttpFoundation\IpUtils

    /**
     * Checks if an IPv4 or IPv6 address is contained in the list of given IPs or subnets
     *
     * @param string       $requestIp IP to check
     * @param string|array $ips       List of IPs or subnets (can be a string if only a single one)
     *
     * @return bool Whether the IP is valid
     */
    public static function checkIp($requestIp, $ips)
    {
        if (!is_array($ips)) {
            $ips = array($ips);
        }

        $method = false !== strpos($requestIp, ':') ? 'checkIp6' : 'checkIp4';

        foreach ($ips as $ip) {
            if (self::$method($requestIp, $ip)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compares two IPv4 addresses.
     * In case a subnet is given, it checks if it contains the request IP.
     *
     * @param string $requestIp IPv4 address to check
     * @param string $ip        IPv4 address or subnet in CIDR notation
     *
     * @return bool Whether the IP is valid
     */
    public static function checkIp4($requestIp, $ip)
    {
        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask < 1 || $netmask > 32) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 32;
        }

        return 0 === substr_compare(sprintf('%032b', ip2long($requestIp)), sprintf('%032b', ip2long($address)), 0, $netmask);
    }

    /**
     * Compares two IPv6 addresses.
     * In case a subnet is given, it checks if it contains the request IP.
     *
     * @author David Soria Parra <dsp at php dot net>
     * @see https://github.com/dsp/v6tools
     *
     * @param string $requestIp IPv6 address to check
     * @param string $ip        IPv6 address or subnet in CIDR notation
     *
     * @return bool Whether the IP is valid
     *
     * @throws \RuntimeException When IPV6 support is not enabled
     */
    public static function checkIp6($requestIp, $ip)
    {
        if (!((extension_loaded('sockets') && defined('AF_INET6')) || @inet_pton('::1'))) {
            throw new RuntimeException('Unable to check Ipv6. Check that PHP was not compiled with option "disable-ipv6".');
        }

        if (false !== strpos($ip, '/')) {
            list($address, $netmask) = explode('/', $ip, 2);

            if ($netmask < 1 || $netmask > 128) {
                return false;
            }
        } else {
            $address = $ip;
            $netmask = 128;
        }

        $bytesAddr = unpack("n*", inet_pton($address));
        $bytesTest = unpack("n*", inet_pton($requestIp));

        for ($i = 1, $ceil = ceil($netmask / 16); $i <= $ceil; $i++) {
            $left = $netmask - 16 * ($i-1);
            $left = ($left <= 16) ? $left : 16;
            $mask = ~(0xffff >> $left) & 0xffff;
            if (($bytesAddr[$i] & $mask) != ($bytesTest[$i] & $mask)) {
                return false;
            }
        }

        return true;
    }
}
