<?php
/**
 * Request
 * 
 * Abstracts the Client Request.
 * 
 * Copyright(c) 2013 Schuyler W Langdon
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * any later version.
 *      
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>.
 * 
 * @note: Bastardized from the MIT licenced Symfony 2 Request Component:
 * 
 * The Symfony Request Component is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with the Symfony 2 source code.
 */
class Request{
    
    public $params = array(
        'requestUri' => null,
        'basePath'   => null,
        'baseUrl'    => null,
        'host'       => null,
        'method'     => null,
        'qs'         => null,
        'secure'     => null
    );
    protected $parsed;
    public $server;
    public $headers;
    protected $skipHeaderCheck = true;
    protected $skipHostCheck = true;//if your app is already restricting the host no need to run checks
    //proxy stuff
    const HEADER_CLIENT_IP    = 'client_ip';
    const HEADER_CLIENT_HOST  = 'client_host';
    const HEADER_CLIENT_PROTO = 'client_proto';
    const HEADER_CLIENT_PORT  = 'client_port';

    //only trust localhost
    public static $trustedProxies = array('127.0.0.1');

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
    public function __construct(){
        //$this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
        //array $get = array(), array $post = array(), array $cookies = array(), array $server = array(
        $this->request = $_POST;
        $this->query = $_GET;
        //$this->attributes = new ParameterBag($attributes);
        $this->cookies = $_COOKIE;
        //$this->files = new FileBag($files);
        $this->server = $_SERVER;
        $this->headers = $this->setHeaders();
        self::$trustedProxies = array_filter(self::$trustedProxies, array($this, 'filterProxies'));
    }

    /**
     *filterProxies
     * 
     * Filters out untrusted proxies
     */ 
    public function filterProxies($proxy){
        return $proxy === $this->getServer('REMOTE_ADDR');
    }

    /**
     * Gets the HTTP headers.
     *
     * @return array
     */
    public function getHeaders($key){
        return (isset($this->headers))
            ? (isset($key) ? (isset($this->headers[$key]) ? $this->headers[$key] : null) : $this->headers)
            : null;
    }
    /**
     * Sets the HTTP headers.
     *
     * @return array
     */
    protected function setHeaders(){
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

            // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
            if ((null !== $authorizationHeader) && (0 === stripos($authorizationHeader, 'basic'))) {
                $exploded = explode(':', base64_decode(substr($authorizationHeader, 6)));
                if (count($exploded) == 2) {
                    list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = $exploded;
                }
            }
        }

        // PHP_AUTH_USER/PHP_AUTH_PW
        if (isset($headers['PHP_AUTH_USER'])) {
            $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
        }

        return $headers;
    }

    protected function getParsed($key){
        if(!isset($this->parsed)){
            $this->parsed = parse_url($this->getRequestUri());
        }
        return isset($this->parsed[$key]) ? $this->parsed[$key] : '';
    }

    public function getBasePath(){
        if (null !== $this->params['basePath']) {
            return $this->params['basePath'];
        }

        return $this->params['basePath'] = $this->getParsed('path');

        $filename = basename($this->getServer('SCRIPT_FILENAME'));
        $baseUrl = $this->getBaseUrl();
        if (empty($baseUrl)) {
            return '';
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
    public function getBaseUrl(){
        if (null !== $this->params['baseUrl']) {
            return $this->params['baseUrl'];
        }
        $filename = basename($this->getServer('SCRIPT_FILENAME'));

        if (basename($this->getServer('SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->getServer('SCRIPT_NAME');
        } elseif (basename($this->getServer('PHP_SELF')) === $filename) {
            $baseUrl = $this->getServer('PHP_SELF');
        } elseif (basename($this->getServer('ORIG_SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->getServer('ORIG_SCRIPT_NAME'); // 1and1 shared hosting compatibility
        } else {
            // Backtrack up the script_filename to find the portion matching
            // php_self
            $path    = $this->getServer('PHP_SELF', '');
            $file    = $this->getServer('SCRIPT_FILENAME', '');
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

    public function getPathInfo(){
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

        if ((null !== $baseUrl) && (false === ($pathInfo = substr($requestUri, strlen($baseUrl))))) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        } elseif (null === $baseUrl) {
            return $requestUri;
        }

        return $this->params['pathInfo'] = (string)$pathInfo;
    }

    public function getRequestUri(){
        if(null !== $this->params['requestUri'] || ($this->skipHeaderCheck && ($this->params['requestUri'] = $this->server['REQUEST_URI']))){
             return $this->params['requestUri'];
        }
        $requestUri = '';
        if (isset($this->headers['X_ORIGINAL_URL'])) {
            // IIS with Microsoft Rewrite Module
            $requestUri = $this->headers['X_ORIGINAL_URL'];
            unset($this->headers['X_ORIGINAL_URL'], $this->server['HTTP_X_ORIGINAL_URL'], $this->server['UNENCODED_URL'], $this->server['IIS_WasUrlRewritten']);
        } elseif (isset($this->headers['X_REWRITE_URL'])) {
            // IIS with ISAPI_Rewrite
            $requestUri = $this->headers['X_REWRITE_URL'];
            unset($this->headers['X_REWRITE_URL']);
        } elseif (isset($this->server['IIS_WasUrlRewritten']) && '1' == $this->server['IIS_WasUrlRewritten']&& !empty($this->server['UNENCODED_URL'])) {
            // IIS7 with URL Rewrite: make sure we get the unencoded url (double slash problem)
            $requestUri = $this->server['UNENCODED_URL'];
            //unset($this->server['UNENCODED_URL'], $this->server['IIS_WasUrlRewritten']);
        } elseif (isset($this->server['REQUEST_URI'])) {
            $requestUri = $this->server['REQUEST_URI'];
            // HTTP proxy reqs setup request uri with scheme and host [and port] + the url path, only use url path
            $schemeAndHttpHost = $this->getSchemeAndHttpHost();
            if (strpos($requestUri, $schemeAndHttpHost) === 0) {
                $requestUri = substr($requestUri, strlen($schemeAndHttpHost));
            }
        } elseif (isset($this->server['ORIG_PATH_INFO'])) {
            // IIS 5.0, PHP as CGI
            $requestUri = $this->server['ORIG_PATH_INFO'];
            if (!empty($this->server['QUERY_STRING'])) {
                $requestUri .= '?'.$this->server->get('QUERY_STRING');
            }
            unset($this->server['ORIG_PATH_INFO']);
        }

        // normalize the request URI to ease creating sub-requests from this request
        $this->server['REQUEST_URI'] = $requestUri;

        return $this->params['requestUri'] = $requestUri;
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
        if(isset($this->params['host'])){
            return $this->params['host'];
        }
        if (self::$trustedProxies && self::$trustedHeaders[self::HEADER_CLIENT_HOST] && $host = $this->getHeaders(self::$trustedHeaders[self::HEADER_CLIENT_HOST])) {
            $elements = explode(',', $host);

            $host = $elements[count($elements) - 1];
        } elseif (!$host = $this->getHeaders('HOST')) {
            if (!$host = $this->getServer('SERVER_NAME')) {
                $host = $this->getServer('SERVER_ADDR', '');
            }
        }

        // trim and remove port number from host
        // host is lowercase as per RFC 952/2181
        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));

        // as the host can come from the user (HTTP_HOST and depending on the configuration, SERVER_NAME too can come from the user)
        // check that it does not contain forbidden characters (see RFC 952 and RFC 2181)
        if (!$this->skipHostCheck && $host && !preg_match('/^\[?(?:[a-zA-Z0-9-:\]_]+\.?)+$/', $host)) {
            //throw new UnexpectedValueException('Invalid Host');
            return false;
        }

        if (!$this->skipHostCheck && !empty(self::$trustedHostPatterns)) {
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
            //throw new UnexpectedValueException('Untrusted Host');
            return false;
        }

        return $this->params['host'] = $host;
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
     * Gets the scheme and HTTP host.
     *
     * If the URL was called with basic authentication, the user
     * and the password are not added to the generated string.
     *
     * @return string The scheme and HTTP host
     */
    public function getSchemeAndHttpHost()
    {
        return $this->getScheme().'://'.$this->getHttpHost();
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
        if(null !== $this->params['secure']){
            return $this->params['secure'];
        }
        if (self::$trustedProxies && isset($this->headers[self::$trustedHeaders[self::HEADER_CLIENT_PROTO]])){
            return $this->params['secure'] = array_key_exists(strtolower($this->headers[self::$trustedHeaders[self::HEADER_CLIENT_PROTO]]), array('https' => true, 'on' => true, '1' => true));
        }

        return $this->params['secure'] = 'on' === strtolower($this->getServer('HTTPS')) || 1 == $this->getServer('HTTPS');
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
        /*if (self::$trustedProxies) {
            if (self::$trustedHeaders[self::HEADER_CLIENT_PORT] && $port = $this->headers->get(self::$trustedHeaders[self::HEADER_CLIENT_PORT])) {
                return $port;
            }

            if (self::$trustedHeaders[self::HEADER_CLIENT_PROTO] && 'https' === $this->headers->get(self::$trustedHeaders[self::HEADER_CLIENT_PROTO], 'http')) {
                return 443;
            }
        }*/

        return $this->getServer('SERVER_PORT');
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
        
        return $this->params['method'] = strtoupper($this->getServer('REQUEST_METHOD', 'GET'));

            if ('POST' === $this->method) {
                if ($method = $this->headers->get('X-HTTP-METHOD-OVERRIDE')) {
                    $this->method = strtoupper($method);
                } elseif (self::$httpMethodParameterOverride) {
                    $this->method = strtoupper($this->request->get('_method', $this->query->get('_method', 'POST')));
                }
            }

        return $this->method;
    }

    public function getQueryString(){
        if(null !== $this->params['qs']){
            return $this->params['qs'];
        }
        return $this->params['qs'] = $this->getParsed('query');
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
            $qs = '?'.$qs;
        }

        return $this->getSchemeAndHttpHost().$this->getBaseUrl().$this->getPathInfo().$qs;
    }

    public function getServer($key, $default = null){
        return array_key_exists($key, $this->server) ? $this->server[$key] : $default;
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
}
