<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * PackageStatusDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\PackageLineItemStatusType|string[] $Status
 * @property DeletionDetail $DeletionDetail
 */
class PackageStatusDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'PackageStatusDetail';
    /**
     * Set Status
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\PackageLineItemStatusType[]|string[] $status
     * @return $this
     */
    public function setStatus(array $status)
    {
        $this->values['Status'] = $status;
        return $this;
    }
    /**
     * Set DeletionDetail
     *
     * @param DeletionDetail $deletionDetail
     * @return $this
     */
    public function setDeletionDetail(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\DeletionDetail $deletionDetail)
    {
        $this->values['DeletionDetail'] = $deletionDetail;
        return $this;
    }
}
