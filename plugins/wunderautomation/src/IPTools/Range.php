<?php

namespace WunderAuto\IPTools;

/**
 * This is a stripped down version if IPTools that just covers
 * the needs in WunderAutomation.
 */
class Range
{
    /**
     * @var IP
     */
    private $firstIP;

    /**
     * @var IP
     */
    private $lastIP;

    /**
     * @var integer
     */
    private $position = 0;

    /**
     * @param IP $firstIP Start IP in range.
     * @param IP $lastIP  End IP in range.
     *
     * @throws \Exception Exception.
     */
    public function __construct(IP $firstIP, IP $lastIP)
    {
        $this->setFirstIP($firstIP);
        $this->setLastIP($lastIP);
    }

    /**
     * Set the start IP in this range
     *
     * @param IP $ip The start IP.
     *
     * @return void
     * @throws \Exception Exception.
     */
    public function setFirstIP(IP $ip)
    {
        if ($this->lastIP && strcmp($ip->inAddr(), $this->lastIP->inAddr()) > 0) {
            throw new \Exception('First IP is grater than second');
        }

        $this->firstIP = $ip;
    }

    /**
     * Set the end IP in this range
     *
     * @param IP $ip The end IP.
     *
     * @return void
     * @throws \Exception Exception.
     */
    public function setLastIP(IP $ip)
    {
        if ($this->firstIP && strcmp($ip->inAddr(), $this->firstIP->inAddr()) < 0) {
            throw new \Exception('Last IP is less than first');
        }

        $this->lastIP = $ip;
    }

    /**
     * Parse a string to a range.
     *
     * @param string $data String representation of a range.
     *
     * @return Range
     * @throws \Exception Exception.
     */
    public static function parse($data)
    {
        if (strpos($data, '-')) {
            list($first, $last) = explode('-', $data, 2);
            $firstIP = IP::parse(trim($first));
            $lastIP  = IP::parse(trim($last));
        } elseif (strpos($data, '/') || strpos($data, ' ')) {
            $network = Network::parse($data);
            $firstIP = $network->getFirstIP();
            $lastIP  = $network->getLastIP();
        } elseif (strpos($data, '*') !== false) {
            $firstIP = IP::parse(str_replace('*', '0', $data));
            $lastIP  = IP::parse(str_replace('*', '255', $data));
        } else {
            $firstIP = IP::parse($data);
            $lastIP  = clone $firstIP;
        }

        return new self($firstIP, $lastIP);
    }

    /**
     * Does an IP exist in this range?
     *
     * @param IP $find The IP Address.
     *
     * @return bool
     * @throws \Exception Exception.
     */
    public function contains(IP $find)
    {
        if ($find instanceof IP) {
            return (strcmp($find->inAddr(), $this->firstIP->inAddr()) >= 0)
                && (strcmp($find->inAddr(), $this->lastIP->inAddr()) <= 0);
        }
        throw new \Exception('Invalid type');
    }
}
