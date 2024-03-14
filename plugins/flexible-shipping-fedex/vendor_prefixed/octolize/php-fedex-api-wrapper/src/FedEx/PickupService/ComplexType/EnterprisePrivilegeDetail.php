<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * EnterprisePrivilegeDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property string $Id
 * @property \FedEx\PickupService\SimpleType\EnterprisePermissionType|string $Permission
 * @property \FedEx\PickupService\SimpleType\CarrierCodeType|string $CarrierCode
 * @property DateRange $EffectiveDateRange
 */
class EnterprisePrivilegeDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'EnterprisePrivilegeDetail';
    /**
     * Set Id
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->values['Id'] = $id;
        return $this;
    }
    /**
     * Set Permission
     *
     * @param \FedEx\PickupService\SimpleType\EnterprisePermissionType|string $permission
     * @return $this
     */
    public function setPermission($permission)
    {
        $this->values['Permission'] = $permission;
        return $this;
    }
    /**
     * Set CarrierCode
     *
     * @param \FedEx\PickupService\SimpleType\CarrierCodeType|string $carrierCode
     * @return $this
     */
    public function setCarrierCode($carrierCode)
    {
        $this->values['CarrierCode'] = $carrierCode;
        return $this;
    }
    /**
     * Set EffectiveDateRange
     *
     * @param DateRange $effectiveDateRange
     * @return $this
     */
    public function setEffectiveDateRange(\FedExVendor\FedEx\PickupService\ComplexType\DateRange $effectiveDateRange)
    {
        $this->values['EffectiveDateRange'] = $effectiveDateRange;
        return $this;
    }
}
