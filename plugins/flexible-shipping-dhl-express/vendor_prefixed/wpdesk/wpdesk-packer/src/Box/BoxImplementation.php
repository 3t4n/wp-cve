<?php

namespace DhlVendor\WPDesk\Packer\Box;

use DhlVendor\WPDesk\Packer\Box;
use DhlVendor\WPDesk\Packer\Item\ItemImplementation;
class BoxImplementation extends \DhlVendor\WPDesk\Packer\Item\ItemImplementation implements \DhlVendor\WPDesk\Packer\Box, \DhlVendor\DVDoug\BoxPacker\Box
{
    const FACTOR = 1000;
    /** @var float|null */
    private $max_weight;
    /** @var string */
    private $name;
    /** @var string */
    private $id;
    /**
     * BoxImplementation constructor.
     *
     * @param float $length
     * @param float $width
     * @param float $height
     * @param float $box_weight
     * @param float|null $max_weight
     * @param string $id
     * @param string $name
     * @param mixed $internal_data
     */
    public function __construct($length, $width, $height, $box_weight, $max_weight, $id, $name = '', $internal_data = null)
    {
        parent::__construct($length, $width, $height, $box_weight, 0, $internal_data);
        $this->max_weight = $max_weight;
        $this->id = $id;
        $this->name = $name;
    }
    /**
     * @return float|null
     */
    public function get_max_weight()
    {
        return $this->max_weight;
    }
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function get_unique_id()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getReference() : string
    {
        return $this->get_unique_id();
    }
    public function getOuterWidth() : int
    {
        return \round($this->get_width() * self::FACTOR);
    }
    public function getOuterLength() : int
    {
        return \round($this->get_length() * self::FACTOR);
    }
    public function getOuterDepth() : int
    {
        return \round($this->get_height() * self::FACTOR);
    }
    public function getEmptyWeight() : int
    {
        return \round($this->get_weight() * self::FACTOR);
    }
    public function getInnerWidth() : int
    {
        return \round($this->get_width() * self::FACTOR);
    }
    public function getInnerLength() : int
    {
        return \round($this->get_length() * self::FACTOR);
    }
    public function getInnerDepth() : int
    {
        return \round($this->get_height() * self::FACTOR);
    }
    public function getMaxWeight() : int
    {
        return \round($this->get_max_weight() * self::FACTOR);
    }
}
