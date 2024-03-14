<?php

namespace UpsFreeVendor\Ups\Entity;

class LabelResults
{
    public $TrackingNumber;
    public $LabelImage;
    public $Receipt;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        $this->LabelImage = new \UpsFreeVendor\Ups\Entity\LabelImage();
        if (null !== $response) {
            if (isset($response->TrackingNumber)) {
                $this->TrackingNumber = $response->TrackingNumber;
            }
            if (isset($response->LabelImage)) {
                $this->LabelImage = new \UpsFreeVendor\Ups\Entity\LabelImage($response->LabelImage);
            }
        }
    }
}
