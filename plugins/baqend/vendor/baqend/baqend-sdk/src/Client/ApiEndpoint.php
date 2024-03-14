<?php

namespace Baqend\SDK\Client;

/**
 * Class ApiEndpoint created on 25.01.2018.
 *
 * @author  Florian Bücklers
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Client
 */
class ApiEndpoint
{

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var int|null
     */
    private $port;

    /**
     * @var string
     */
    private $basePath;

    /**
     * Creates an API endpoint.
     *
     * @param string|array $connectTo An API specification to connect to.
     * @throws \InvalidArgumentException When the argument is not a valid endpoint specification.
     */
    public function __construct($connectTo) {
        // Is it an array configuration?
        if (is_array($connectTo)) {
            $scheme = isset($connectTo['scheme']) ?  $connectTo['scheme'] : 'https';
            $port = isset($connectTo['port']) && $connectTo['port'] !== null ? (int) $connectTo['port'] : null;
            $hostname = isset($connectTo['host']) ?  $connectTo['host'] : 'local.baqend.com';
            $basePath = isset($connectTo['basePath']) ?  $connectTo['basePath'] : '';

            $this->init($scheme, $hostname, $port, $basePath);
            return;
        }

        if (!is_string($connectTo)) {
            throw $this->createException();
        }

        // Is it a valid Baqend app?
        if (preg_match('/^[a-z]([a-z0-9-]*[a-z0-9])*$/', $connectTo) === 1) {
            $scheme = 'https';
            $port = null;
            $hostname = $connectTo.'.app.baqend.com';
            $basePath = '/v1';

            $this->init($scheme, $hostname, $port, $basePath);
            return;
        }

        // Is it a valid URL?    scheme       hostname            port     basepath
        if (preg_match('/^(https?):\/\/([^\/:]+|\[[^\]]+]+)(:(\d*)|)(\/\w+|)\/?$/', $connectTo, $matches) === 1) {
            list (, $scheme, $hostname, , $port, $basePath) = $matches;

            // Remove IPv6 square brackets
            $hostname = preg_replace('/[[\]]/', '', $hostname);

            // Cast port
            $port = $port ? (int) $port : null;

            $this->init($scheme, $hostname, $port, $basePath);
            return;
        }

        throw $this->createException();
    }

    /**
     * @return string
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getHostname() {
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getHost() {
        if ($this->port === null) {
            return $this->hostname;
        }

        return $this->hostname.':'.$this->port;
    }

    /**
     * @return int|null
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getBasePath() {
        return $this->basePath;
    }

    /**
     * @inheritDoc
     */
    public function __toString() {
        return sprintf('%s://%s%s', $this->scheme, $this->getHost(), $this->basePath);
    }

    /**
     * @param string $scheme
     * @param string $hostname
     * @param int|null $port
     * @param string $basePath
     */
    private function init($scheme, $hostname, $port, $basePath) {
        $this->scheme = $scheme;
        $this->hostname = $hostname;
        $this->port = $port;
        $this->basePath = $basePath;
    }

    /**
     * @return \InvalidArgumentException
     */
    private function createException() {
        return new \InvalidArgumentException('You must connect to an app name or API endpoint URL as string or array');
    }
}
