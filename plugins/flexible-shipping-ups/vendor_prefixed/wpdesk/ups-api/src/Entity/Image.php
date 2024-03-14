<?php

namespace UpsFreeVendor\Ups\Entity;

class Image
{
    public $ImageFormat;
    public $GraphicImage;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        if (null !== $response) {
            if (isset($response->ImageFormat)) {
                $this->ImageFormat = new \UpsFreeVendor\Ups\Entity\ImageFormat($response->ImageFormat);
            }
            if (isset($response->GraphicImage)) {
                $this->GraphicImage = $response->GraphicImage;
            }
        }
    }
}
