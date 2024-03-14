<?php

namespace WunderAuto\IPTools;

/**
 * This is a stripped down version if IPTools that just covers
 * the needs in WunderAutomation.
 */
class IP
{
    const IP_V4 = 'IPv4';
    const IP_V6 = 'IPv6';

    const IP_V4_MAX_PREFIX_LENGTH = 32;
    const IP_V6_MAX_PREFIX_LENGTH = 128;

    const IP_V4_OCTETS = 4;
    const IP_V6_OCTETS = 16;

    /**
     * @var string
     */
    private $in_addr;

    /**
     * IP constructor.
     *
     * @param string $ip Ip address.
     *
     * @throws \Exception When $ip is not a valid ip address.
     */
    public function __construct($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \Exception("Invalid IP address format");
        }
        $this->in_addr = inet_pton($ip);
    }

    /**
     * Create a new instance of IP from string IP
     *
     * @param string $ip Ip address.
     *
     * @return IP
     *
     * @throws \Exception When $ip is not a valid ip address.
     */
    public static function parse($ip)
    {
        return new self($ip);
    }

    /**
     * Create a new instance of IP from binary representation of IP
     *
     * @param string $binIP Binary IP address.
     *
     * @return IP
     * @throws \Exception When $binIP is not a valid ip address.
     *
     */
    public static function parseBin($binIP)
    {
        if (!preg_match('/^([0-1]{32}|[0-1]{128})$/', $binIP)) {
            throw new \Exception("Invalid binary IP address format");
        }

        $in_addr = '';
        foreach (array_map('bindec', str_split($binIP, 8)) as $char) {
            $in_addr .= pack('C*', $char);
        }

        return new self(inet_ntop($in_addr));
    }

    /**
     * @return integer
     */
    public function getMaxPrefixLength()
    {
        return $this->getVersion() === self::IP_V4
            ? self::IP_V4_MAX_PREFIX_LENGTH
            : self::IP_V6_MAX_PREFIX_LENGTH;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        $version = '';

        if (filter_var(inet_ntop($this->in_addr), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $version = self::IP_V4;
        } elseif (filter_var(inet_ntop($this->in_addr), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $version = self::IP_V6;
        }

        return $version;
    }

    /**
     * @return string
     */
    public function inAddr()
    {
        return $this->in_addr;
    }

    /**
     * @return string
     */
    public function toBin()
    {
        $binary = array();
        foreach (unpack('C*', $this->in_addr) as $char) {
            $binary[] = str_pad(decbin($char), 8, '0', STR_PAD_LEFT);
        }

        return implode($binary);
    }
}
