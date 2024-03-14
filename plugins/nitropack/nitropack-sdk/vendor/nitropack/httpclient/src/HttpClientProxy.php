<?php
namespace NitroPack\HttpClient;

class HttpClientProxy {
    protected $addr;
    protected $port;
    protected $forceOnPrivate;
    protected $nextProxy;

    public function __construct($ip, $port, $forceOnPrivate = false, $nextProxy = NULL) {
        $this->addr = $ip;
        $this->port = $port ? $port : 1080;
        $this->forceOnPrivate = $forceOnPrivate;
        $this->nextProxy = $nextProxy;
    }

    public function getAddr() {
        return $this->addr;
    }

    public function getPort() {
        return $this->port;
    }

    public function shouldForceOnPrivate() {
        return $this->forceOnPrivate;
    }

    public function setNextProxy($proxy) {
        $this->nextProxy = $proxy;
    }

    public function getNextProxy() {
        return $this->nextProxy;
    }
}