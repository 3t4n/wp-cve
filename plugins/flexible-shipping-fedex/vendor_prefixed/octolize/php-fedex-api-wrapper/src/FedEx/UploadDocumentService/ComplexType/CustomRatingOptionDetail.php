<?php

namespace FedExVendor\FedEx\UploadDocumentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CustomRatingOptionDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 *
 * @property \FedEx\UploadDocumentService\SimpleType\CustomRatingOptionType|string[] $Options
 * @property CustomDiscountExclusionDetail $CustomDiscountExclusionDetail
 */
class CustomRatingOptionDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CustomRatingOptionDetail';
    /**
     * Set Options
     *
     * @param \FedEx\UploadDocumentService\SimpleType\CustomRatingOptionType[]|string[] $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->values['Options'] = $options;
        return $this;
    }
    /**
     * Set CustomDiscountExclusionDetail
     *
     * @param CustomDiscountExclusionDetail $customDiscountExclusionDetail
     * @return $this
     */
    public function setCustomDiscountExclusionDetail(\FedExVendor\FedEx\UploadDocumentService\ComplexType\CustomDiscountExclusionDetail $customDiscountExclusionDetail)
    {
        $this->values['CustomDiscountExclusionDetail'] = $customDiscountExclusionDetail;
        return $this;
    }
}
