<?php

namespace UpsFreeVendor\Octolize\Blocks\PickupPoint;

class SelectPickupPointException extends \Exception
{
    public function __construct()
    {
        parent::__construct(\__('Please select a pickup point.', 'flexible-shipping-ups'));
    }
}
