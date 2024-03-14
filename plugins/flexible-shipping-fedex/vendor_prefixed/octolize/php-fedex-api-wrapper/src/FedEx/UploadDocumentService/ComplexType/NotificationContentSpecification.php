<?php

namespace FedExVendor\FedEx\UploadDocumentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * NotificationContentSpecification
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 *
 * @property \FedEx\UploadDocumentService\SimpleType\NotificationContentType|string[] $Exclusions
 */
class NotificationContentSpecification extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'NotificationContentSpecification';
    /**
     * Set Exclusions
     *
     * @param \FedEx\UploadDocumentService\SimpleType\NotificationContentType[]|string[] $exclusions
     * @return $this
     */
    public function setExclusions(array $exclusions)
    {
        $this->values['Exclusions'] = $exclusions;
        return $this;
    }
}
