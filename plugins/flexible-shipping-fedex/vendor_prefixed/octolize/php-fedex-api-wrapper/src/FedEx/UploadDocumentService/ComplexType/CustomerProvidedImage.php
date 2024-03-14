<?php

namespace FedExVendor\FedEx\UploadDocumentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CustomerProvidedImage
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 *
 * @property string $Image
 * @property ImageReferenceDetail $ImageReferenceDetail
 */
class CustomerProvidedImage extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CustomerProvidedImage';
    /**
     * Set Image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->values['Image'] = $image;
        return $this;
    }
    /**
     * Set ImageReferenceDetail
     *
     * @param ImageReferenceDetail $imageReferenceDetail
     * @return $this
     */
    public function setImageReferenceDetail(\FedExVendor\FedEx\UploadDocumentService\ComplexType\ImageReferenceDetail $imageReferenceDetail)
    {
        $this->values['ImageReferenceDetail'] = $imageReferenceDetail;
        return $this;
    }
}
