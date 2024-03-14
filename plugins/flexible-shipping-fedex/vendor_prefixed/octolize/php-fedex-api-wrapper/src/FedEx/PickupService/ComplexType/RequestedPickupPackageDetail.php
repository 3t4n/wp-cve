<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * RequestedPickupPackageDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property PickupPackageSpecialServicesRequested $PackageSpecialServices
 * @property Weight $Weight
 */
class RequestedPickupPackageDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'RequestedPickupPackageDetail';
    /**
     * Set PackageSpecialServices
     *
     * @param PickupPackageSpecialServicesRequested $packageSpecialServices
     * @return $this
     */
    public function setPackageSpecialServices(\FedExVendor\FedEx\PickupService\ComplexType\PickupPackageSpecialServicesRequested $packageSpecialServices)
    {
        $this->values['PackageSpecialServices'] = $packageSpecialServices;
        return $this;
    }
    /**
     * Set Weight
     *
     * @param Weight $weight
     * @return $this
     */
    public function setWeight(\FedExVendor\FedEx\PickupService\ComplexType\Weight $weight)
    {
        $this->values['Weight'] = $weight;
        return $this;
    }
}
