<?php

namespace WunderAuto\IPTools;

/**
 * This is a stripped down version if IPTools that just covers
 * the needs in WunderAutomation.
 */
class Network
{
    /**
     * @var IP
     */
    private $ip;
    /**
     * @var IP
     */
    private $netmask;

    /**
     * @var integer
     */
    private $position = 0;

    /**
     * Network constructor.
     *
     * @param IP $ip      Base IP.
     * @param IP $netmask Netmask.
     *
     * @throws \Exception Exception.
     */
    public function __construct(IP $ip, IP $netmask)
    {
        $this->setIP($ip);
        $this->setNetmask($netmask);
    }

    /**
     * Parse a network represented as a string (range, netmask, significant bits etc).
     *
     * @param string $data String Representation of a network.
     *
     * @return Network
     * @throws \Exception Exception.
     */
    public static function parse($data)
    {
        if (preg_match('~^(.+?)/(\d+)$~', $data, $matches)) {
            $ip      = IP::parse($matches[1]);
            $netmask = self::prefix2netmask((int)$matches[2], $ip->getVersion());
        } elseif (strpos($data, ' ')) {
            list($ip, $netmask) = explode(' ', $data, 2);
            $ip      = IP::parse($ip);
            $netmask = IP::parse($netmask);
        } else {
            $ip      = IP::parse($data);
            $netmask = self::prefix2netmask($ip->getMaxPrefixLength(), $ip->getVersion());
        }

        return new self($ip, $netmask);
    }

    /**
     * Convert a prefix to a netmask
     *
     * @param integer $prefixLength Prefix length.
     * @param string  $version      Version.
     *
     * @return IP
     * @throws \Exception Exception.
     */
    public static function prefix2netmask($prefixLength, $version)
    {
        if (!in_array($version, array(IP::IP_V4, IP::IP_V6))) {
            throw new \Exception("Wrong IP version");
        }

        $maxPrefixLength = $version === IP::IP_V4
            ? IP::IP_V4_MAX_PREFIX_LENGTH
            : IP::IP_V6_MAX_PREFIX_LENGTH;

        if (!is_numeric($prefixLength) || !($prefixLength >= 0 && $prefixLength <= $maxPrefixLength)) {
            throw new \Exception('Invalid prefix length');
        }

        $binIP = str_pad(str_pad('', (int)$prefixLength, '1'), $maxPrefixLength, '0');

        return IP::parseBin($binIP);
    }

    /**
     * @return IP
     */
    public function getFirstIP()
    {
        return $this->getNetwork();
    }

    /**
     * @return IP
     */
    public function getNetwork()
    {
        return new IP(inet_ntop($this->getIP()->inAddr() & $this->getNetmask()->inAddr()));
    }

    /**
     * @return IP
     */
    public function getIP()
    {
        return $this->ip;
    }

    /**
     * Set internal IP
     *
     * @param IP $ip The IP address.
     *
     * @return void
     * @throws \Exception Exception.
     */
    public function setIP(IP $ip)
    {
        if (isset($this->netmask) && $this->netmask->getVersion() !== $ip->getVersion()) {
            throw new \Exception('IP version is not same as Netmask version');
        }

        $this->ip = $ip;
    }

    /**
     * @return IP
     */
    public function getNetmask()
    {
        return $this->netmask;
    }

    /**
     * Set the netmask
     *
     * @param IP $ip The netmask.
     *
     * @return void
     * @throws \Exception Exception.
     */
    public function setNetmask(IP $ip)
    {
        if (!preg_match('/^1*0*$/', $ip->toBin())) {
            throw new \Exception('Invalid Netmask address format');
        }

        if (isset($this->ip) && $ip->getVersion() !== $this->ip->getVersion()) {
            throw new \Exception('Netmask version is not same as IP version');
        }

        $this->netmask = $ip;
    }

    /**
     * @return IP
     */
    public function getLastIP()
    {
        return $this->getBroadcast();
    }

    /**
     * @return IP
     */
    public function getBroadcast()
    {
        return new IP(inet_ntop($this->getNetwork()->inAddr() | ~$this->getNetmask()->inAddr()));
    }
}
