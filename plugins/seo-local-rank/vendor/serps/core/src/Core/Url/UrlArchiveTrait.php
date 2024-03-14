<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

use Serps\Core\Url;
use Serps\Core\Url\UrlArchiveInterface;
use Serps\Core\Url\QueryParam;
use Serps\Core\UrlArchive;

/**
 * This trait offers implementation for @see UrlArchiveInterface
 * @method static build($scheme, $host, $path=null, array $query=[], $hash = null, $port = null, $user = null, $pass)
 */
trait UrlArchiveTrait
{

    // info: build static method is declared in docblock instead of as a abstract static method,
    // because before php7 it was considered as an error to have an abstract static method in a trait

    protected $hash;
    protected $path;
    protected $scheme;

    /**
     * @var QueryParam[]
     */
    protected $query = [];

    /**
     * host name e.g: ``www.example.com``
     */
    protected $host;

    protected $user;
    protected $pass;
    protected $port;


    private function initWithDefaults(
        $scheme = null,
        $host = null,
        $path = null,
        array $query = [],
        $hash = null,
        $port = null,
        $user = null,
        $pass = null
    ) {

        $this->host = $host;
        $this->scheme = $scheme;
        $this->path = $path ;
        $this->hash = $hash;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;

        $this->query = [];
        foreach ($query as $k => $v) {
            if (is_object($v)) {
                if ($v instanceof QueryParam) {
                    $this->query[$v->getName()] = clone $v;
                } else {
                    throw new \InvalidArgumentException('invalid query param item');
                }
            } else {
                $this->query[$k] = new QueryParam($k, $v);
            }
        }
    }

    /**
     * Alternative to the builtin parse_str php function that will replace periods with underscores
     *
     * Inspired by @elyobo solution (see link)
     *
     * @link https://gist.github.com/elyobo/6200838
     */
    public static function parseStr($str)
    {

        if (!$str) {
            return [];
        }

        $foundKeys = [];
        $finalKeys = [];


        $source = preg_replace_callback(
            '/
            # Match at start of string or &
            (?:^|(?<=&))
            # Exclude cases where the period is in brackets, e.g. foo[bar.blarg]
            [^=&\[]*
            # Affected cases: periods and spaces
            (?:\.|%20| )
            # Keep matching until assignment, next variable, end of string or
            # start of an array
            [^=&\[]*
            /x',
            function ($key) use (&$foundKeys) {
                $foundKeys[] = $key = base64_encode(urldecode($key[0]));
                return urlencode($key);
            },
            $str
        );

        parse_str($source, $data);

        foreach ($data as $key => $val) {
            // Only unprocess encoded keys

            if (!in_array($key, $foundKeys)) {
                $finalKeys[$key] = $val;
            } else {
                $key = base64_decode($key);
                $finalKeys[$key] = $val;
            }
        }

        return $finalKeys;
    }

    /**
     * @param array $urlItems
     * @return static
     */
    public static function fromArray(array $urlItems)
    {

        if (isset($urlItems['query'])) {
            $query = self::parseStr($urlItems['query']);
        } else {
            $query = [];
        }

        return static::build(
            isset($urlItems['scheme']) ? $urlItems['scheme'] : null,
            isset($urlItems['host']) ? $urlItems['host'] : null,
            isset($urlItems['path']) ? $urlItems['path'] : null,
            $query,
            isset($urlItems['fragment']) ? $urlItems['fragment'] : null,
            isset($urlItems['port']) ? $urlItems['port'] : null,
            isset($urlItems['user']) ? $urlItems['user'] : null,
            isset($urlItems['path']) ? $urlItems['path'] : null
        );
    }

    protected static function parseUrl($url)
    {
        // Normally a URI must be ASCII, however. However, often it's not and
        // parse_url might corrupt these strings.
        //
        // For that reason we take any non-ascii characters from the uri and
        // uriencode them first.
        //
        // code from https://github.com/fruux/sabre-uri
        $url = preg_replace_callback(
            '/[^[:ascii:]]/u',
            function ($matches) {
                return rawurlencode($matches[0]);
            },
            $url
        );

        return parse_url($url);
    }

    /**
     * Builds an url instance from an url string
     * @param string $url the url to parse
     * @return static
     */
    public static function fromString($url)
    {
        $urlItems = self::parseUrl($url);
        return static::fromArray($urlItems);
    }

    /**
     * @return QueryParam[]
     */
    public function getParams()
    {
        return $this->query;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getPort()
    {
        return $this->port;
    }


    /**
     * Set the scheme.
     * ``foo`` in ``http://www.example.com#foo``
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set the scheme.
     * ``some/path`` in ``http://www.example.com/some/path``
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the scheme.
     * ``http`` in ``http://www.example.com``
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Get the hostname.
     * ``www.example.com`` in ``http://www.example.com``
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParamValue($name, $default = null)
    {
        if (isset($this->query[$name])) {
            return $this->query[$name]->getValue();
        }
        return $default;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParamRawValue($name, $default = null)
    {
        if (isset($this->query[$name])) {
            return $this->query[$name]->getRawValue();
        }
        return $default;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($this->query[$name]);
    }

    public function getAuthority()
    {
        $authority = '';

        if ($host = $this->getHost()) {
            if ($user = $this->getUser()) {
                $authority .= $user;
                if ($pass = $this->getPass()) {
                    $authority .= ':' . $pass;
                }
                $authority .= '@';
            }

            $authority .= $this->getHost();

            if ($port = $this->getPort()) {
                if (!(80 == $port && 'http' === $this->getScheme())
                    && !(443 == $port && 'https' === $this->getScheme() )
                ) {
                    $authority .= ':' . $port;
                }
            }
        }

        return $authority;
    }

    /**
     * Get the full uri: ``http://www.example.com/path?param=value#hash``
     * @return string
     */
    public function buildUrl()
    {
        $scheme = $this->getScheme();
        if ($scheme) {
            $uri = $scheme . ':';
        } else {
            $uri = '';
        }

        if ($authority = $this->getAuthority()) {
            $uri .= '//' . $authority;
        }

        if ($path = $this->getPath()) {
            $uri .= '/' . ltrim($path, '/');
        }

        if ($query = $this->getQueryString()) {
            $uri .= '?' . $query;
        }

        if ($hash = $this->getHash()) {
            $uri .= '#' . $this->getHash();
        }


        return $uri;
    }

    public function __toString()
    {
        return $this->buildUrl();
    }

    /**
     * Get the query string.
     * ``foo=bar&bar=foo`` in ``http://www.example.com?foo=bar&bar=foo``
     * @return string
     */
    public function getQueryString()
    {
        return implode('&', $this->query);
    }

    /**
     * Resolve the given url, and returns it as an alterable url, that's made for minor performance update between
     * resolve and resolveAsString (thus resolveAsString does not need double transformation of the url)
     * This method must remain private
     *
     * @param string $a class to resolve to, must be an alterableUrl class
     * @param string $url url to resolve
     * @return AlterableUrlInterface
     */
    private function resolveAsAlterableUrl($url, $as)
    {
        $delta = call_user_func([$as, 'fromString'], $url);

        if (!($scheme = $delta->getScheme())) {
            $delta->setScheme($this->getScheme());

            $path = $delta->getPath();
            if (empty($delta->getAuthority())) {
                $delta->setUser($this->getUser());
                $delta->setPass($this->getPass());
                $delta->setHost($this->getHost());
                $delta->setPort($this->getPort());

                if (empty($path)) {
                    $path = $this->getPath();
                    if (empty($delta->getParams())) {
                        $delta->setParams($this->getParams());
                    }
                } elseif ('/' !== $path{0}) {
                    $path = $this->getPath();
                    if (strpos($path, '/') !== false) {
                        $path = substr($path, 0, strrpos($path, '/'));
                    }
                    $path .= '/' . $delta->getPath();
                }
            }

            // Removing .. and .
            $pathParts = explode('/', $path);
            $newPathParts = [];
            foreach ($pathParts as $pathPart) {
                switch ($pathPart) {
                    //case '' :
                    case '.':
                        break;
                    case '..':
                        array_pop($newPathParts);
                        break;
                    default:
                        $newPathParts[] = $pathPart;
                        break;
                }
            }
            $path = implode('/', $newPathParts);

            // If ends with . or .. we want to preserve / at end
            $lastItem = end($pathParts);
            if ('.' === $lastItem || '..' === $lastItem) {
                $path .= '/';
            }

            $delta->setPath($path);

            // In every cases we want to keep $delta hash
        }

        return $delta;
    }

    /**
     * @see UrlArchiveInterface::resolve
     */
    public function resolve($url, $as = null)
    {
        if (null === $as) {
            $as = static::class;
            $implements = class_implements($as, true);
        } else {
            if (!is_string($as)) {
                throw new \InvalidArgumentException(
                    'Invalid argument for UrlArchive::resolve(), the class name must be a string'
                );
            } elseif (!class_exists($as, true)) {
                throw new \InvalidArgumentException($as . ' class does not exist');
            }

            // Check if the given class implements urlArchive
            $implements = class_implements($as, true);

            if (!in_array(UrlArchiveInterface::class, $implements)) {
                throw new \InvalidArgumentException(
                    'Invalid argument for ' . __CLASS__ . '::' . __METHOD__ . ', the specified class must implement'
                    . UrlArchiveInterface::class
                );
            }
        }

        // If not resolved as an alterable url we need to use an alterable url and to transform it latter
        if (!in_array(AlterableUrlInterface::class, $implements)) {
            return $this->resolveAsAlterableUrl($url, Url::class)->cloneAs($as);
        } else {
            return $this->resolveAsAlterableUrl($url, Url::class);
        }
    }

    public function resolveAsString($url)
    {
        return $this->resolveAsAlterableUrl($url, Url::class)->buildUrl();
    }

    /**
     * @param null $as
     * @return UrlArchiveInterface
     */
    public function cloneAs($as = null)
    {

        if (null === $as) {
            $as = static::class;
        } else {
            if (!is_string($as)) {
                throw new \InvalidArgumentException('Invalid argument for ' . static::class . '::' . __METHOD__);
            } elseif (!class_exists($as, true)) {
                throw new \InvalidArgumentException($as . ' class does not exist');
            }

            // Check if the given class implements urlArchive
            $implements = class_implements($as, true);

            if (!in_array(UrlArchiveInterface::class, $implements)) {
                throw new \InvalidArgumentException(
                    'Invalid argument for ' . __CLASS__ . '::' . __METHOD__ . '(), the specified class must implement'
                    . UrlArchiveInterface::class
                );
            }
        }

        return call_user_func(
            [$as, 'build'],
            $this->getScheme(),
            $this->getHost(),
            $this->getPath(),
            $this->getParams(),
            $this->getHash(),
            $this->getPort(),
            $this->getUser(),
            $this->getPass()
        );
    }

    public function __clone()
    {
        return $this->cloneAs();
    }
}
