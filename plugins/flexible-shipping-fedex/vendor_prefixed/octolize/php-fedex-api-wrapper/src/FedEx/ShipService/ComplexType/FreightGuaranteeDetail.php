<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * FreightGuaranteeDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property \FedEx\ShipService\SimpleType\FreightGuaranteeType|string $Type
 * @property string $Date
 * @property string $Time
 */
class FreightGuaranteeDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'FreightGuaranteeDetail';
    /**
     * Set Type
     *
     * @param \FedEx\ShipService\SimpleType\FreightGuaranteeType|string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->values['Type'] = $type;
        return $this;
    }
    /**
     * Date for all Freight guarantee types.
     *
     * @param string $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->values['Date'] = $date;
        return $this;
    }
    /**
     * Time for GUARANTEED_TIME only.
     *
     * @param string $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->values['Time'] = $time;
        return $this;
    }
}
