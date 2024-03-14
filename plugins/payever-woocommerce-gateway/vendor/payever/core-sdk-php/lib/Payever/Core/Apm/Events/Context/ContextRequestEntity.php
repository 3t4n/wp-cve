<?php

/**
 * PHP version 5.4 and 8.1
 *
 * @category  Apm Agent
 * @package   Payever\Core
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Core\Apm\Events\Context;

use Payever\Sdk\Core\Http\ApmRequestEntity;
use Payever\Sdk\Core\Apm\Events\Context\Request\HeadersEntity;
use Payever\Sdk\Core\Apm\Events\Context\Request\SocketEntity;
use Payever\Sdk\Core\Apm\Events\Context\Request\UrlEntity;

/**
 * Class RequestEntity
 * @method string getMethod()
 * @method SocketEntity getSocket()
 * @method UrlEntity getUrl()
 * @method HeadersEntity getHeaders()
 * @method self setMethod(string $method)
 */
class ContextRequestEntity extends ApmRequestEntity
{
    const METHOD_CLI = 'cli';

    /** @var string $http_version */
    protected $http_version;

    /** @var string $method */
    protected $method;

    /** @var SocketEntity $socket */
    protected $socket;

    /** @var UrlEntity $url */
    protected $url;

    /** @var HeadersEntity $headers */
    protected $headers;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!isset($data['http_version'])) {
            $data['http_version'] = $this->getProtocolVersion();
        }

        if (!isset($data['method'])) {
            $data['method'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::METHOD_CLI;
        }

        if (!isset($data['socket'])) {
            $data['socket'] = new SocketEntity();
        }

        if (!isset($data['url'])) {
            $data['url'] = new UrlEntity();
        }

        if (!isset($data['headers'])) {
            $data['headers'] = new HeadersEntity();
        }

        parent::__construct($data);
    }

    /**
     * @param SocketEntity|string|array $socket
     * @return $this
     */
    public function setSocket($socket)
    {
        $this->socket = $this->getClassInstance(SocketEntity::class, $socket);

        return $this;
    }

    /**
     * @param UrlEntity|string|array $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $this->getClassInstance(UrlEntity::class, $url);

        return $this;
    }

    /**
     * @param HeadersEntity|string|array $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $this->getClassInstance(HeadersEntity::class, $headers);

        return $this;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setHttpVersion($version)
    {
        $this->http_version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpVersion()
    {
        return $this->http_version;
    }

    /**
     * @return false|string|null
     */
    private function getProtocolVersion()
    {
        $protocolVersion = null;
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $SERVER_PROTOCOL = $_SERVER['SERVER_PROTOCOL'] ?: '';
            $protocolVersion = substr($SERVER_PROTOCOL, strpos($SERVER_PROTOCOL, DIRECTORY_SEPARATOR) + 1);
        }

        return $protocolVersion;
    }
}
