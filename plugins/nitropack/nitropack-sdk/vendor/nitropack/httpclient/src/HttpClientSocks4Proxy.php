<?php

namespace NitroPack\HttpClient;

use \NitroPack\HttpClient\Exceptions\ProxyConnectException;
use \NitroPack\HttpClient\Exceptions\ProxyConnectTimedOutException;

class HttpClientSocks4Proxy extends HttpClientSocksProxy {
    private $isConnectPending;
    private $isNextProxyPending;
    private $resolveOnProxy;
    private $sendFrame;
    private $recvFrame;
    private $lastIo;
    private $connectTime;
    private $connectStart;
    private $connectStage;

    public function __construct($ip, $port, $resolveOnProxy = false) {
        parent::__construct($ip, $port);

        $this->isConnectPending = false;
        $this->resolveOnProxy = $resolveOnProxy;
        $this->sendFrame = "";
        $this->recvFrame = "";
        $this->lastIo = 0;
    }

    public function getConnectTime() {
        return $this->connectTime;
    }

    public function resolveOnProxy($status) {
        $this->resolveOnProxy = $status;
    }

    public function connect($sock, $destIp, $destPort, $domain = NULL) {
        $ip = $this->nextProxy ? $this->nextProxy->getAddr() : $destIp;
        $port = $this->nextProxy ? $this->nextProxy->getPort() : $destPort;

        $this->prepareSendFrame($ip, $port, $domain);
        $this->recvFrame = "";

        if (@fwrite($sock, $this->sendFrame) === false) {
            throw new ProxyConnectException("Proxy communication error: cannot send CONNECT frame");
        }

        $this->recvFrame = @fread($sock, 8);

        if (!$this->recvFrame) {
            $metaData = stream_get_meta_data($sock);
            if (!empty($metaData["timed_out"])) {
                throw new ProxyConnectTimedOutException("Connecting to server timed out");
            } else {
                throw new ProxyConnectException("Empty proxy response");
            }
        }

        $bytes = unpack("CVN/CCD/SDSTPORT/LDSTIP", $this->recvFrame);

        if ($bytes["CD"] != 90) {
            throw new ProxyConnectException("Connection rejected by proxy with code " . $bytes["CD"]);
        }

        if ($this->nextProxy) {
            return $this->nextProxy->connect($sock, $destIp, $destPort, $domain);
        }

        return true;
    }

    public function connectAsync($sock, $destIp, $destPort, $domain = NULL, $timeout = 5) {
        $ip = $this->nextProxy ? $this->nextProxy->getAddr() : $destIp;
        $port = $this->nextProxy ? $this->nextProxy->getPort() : $destPort;

        $now = microtime(true);
        if (!$this->isConnectPending) {
            $this->isNextProxyPending = $this->nextProxy ? true : false;
            $this->prepareSendFrame($ip, $port, $domain);
            $this->recvFrame = "";
            $this->isConnectPending = true;
            $this->lastIo = $now;
            $this->connectStart = $now;
            $this->connectStage = "LOCAL";
        }

        if ($this->connectStage == "LOCAL") {
            if ($timeout && $now - $this->lastIo >= $timeout) {
                $this->connectTime = $now - $this->connectStart;
                $this->isConnectPending = false;
                throw new ProxyConnectTimedOutException(sprintf("Proxy connect timed out: Connecting to origin took more than %s
                    seconds", $timeout));
            }

            if ($this->sendFrame) { // We have more data to send here
                $written = @fwrite($sock, $this->sendFrame);
                if ($written === false) {
                    $this->isConnectPending = false;
                    $this->connectTime = $now - $this->connectStart;
                    throw new ProxyConnectException("Proxy communication error: cannot send CONNECT frame");
                } else {
                    $this->sendFrame = substr($this->sendFrame, $written);
                    if (!$this->sendFrame) {
                        $this->lastIo = microtime(true);
                    }
                    return false;
                }
            } else { // Read te proxy's reply
                $this->recvFrame .= @fread($sock, 8);

                if ($this->recvFrame === false) {
                    $this->isConnectPending = false;
                    $this->connectTime = $now - $this->connectStart;
                    throw new ProxyConnectException("Proxy communication error: cannot read CONNECT reply frame");
                } else if (strlen($this->recvFrame) == 8) {
                    $bytes = unpack("CVN/CCD/SDSTPORT/LDSTIP", $this->recvFrame);
                    $this->connectTime = $now - $this->connectStart;

                    if ($this->isNextProxyPending) {
                        $this->connectStage = "NEXT";
                    } else {
                        $this->isConnectPending = false;
                    }

                    if ($bytes["CD"] != 90) {
                        throw new ProxyConnectException("Connection rejected by proxy with code " . $bytes["CD"]);
                    }
                } else {
                    return false;
                }
            }
        }

        if ($this->isNextProxyPending) {
            $proxyRes = $this->nextProxy->connectAsync($sock, $destIp, $destPort, $domain, $timeout);
            if ($proxyRes === true) {
                $this->isConnectPending = false;
                $this->isNextProxyPending = false;
            }
            return $proxyRes;
        } else {
            $this->isConnectPending = false;
            return true;
        }
    }

    private function prepareSendFrame($ip, $port, $domain = NULL) {
        if ($domain && $this->resolveOnProxy) $ip = "0.0.0.1";

        $ipInt = ip2long($ip);
        if ($ipInt === -1 || $ipInt === false) {
            // It's possible that $ip is a hostname, not an IP, so try to resolve it.
            // This can happen when nextProxy is set and it's address is a hostname.
            $ips = gethostbynamel($ip);
            if ($ips) {
                foreach ($ips as $currentIp) {
                    $ipInt = ip2long($currentIp);
                    if ($ipInt === -1 || $ipInt === false) {
                        continue;
                    } else {
                        break;
                    }
                }

                if ($ipInt === -1 || $ipInt === false) {
                    throw new ProxyConnectException("Invalid destination IP");
                }
            }
        }

        $this->sendFrame = pack("CCnNx", 4, 1, $port, $ipInt);

        if ($domain && $this->resolveOnProxy) {
            $this->sendFrame .= $domain . pack("x");
        }
    }
}