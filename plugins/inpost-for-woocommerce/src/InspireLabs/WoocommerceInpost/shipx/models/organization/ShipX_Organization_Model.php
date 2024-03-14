<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\organization;

use InspireLabs\WoocommerceInpost\shipx\models\organization\services\ShipX_Service_Model;

class ShipX_Organization_Model
{
    /**
     * @var ShipX_Service_Model[]
     */
    private $services;

    /**
     * @return ShipX_Service_Model[]
     */
    public function getServices(): array {
        return $this->services;
    }

    /**
     * @param ShipX_Service_Model[] $services
     */
    public function setServices($services)
    {
        $this->services = $services;
    }
}
