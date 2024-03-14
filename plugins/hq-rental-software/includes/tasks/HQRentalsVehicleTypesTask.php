<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector as Connector;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleType;

class HQRentalsVehicleTypesTask extends HQRentalsBaseTask
{
    public function __construct()
    {
        $this->connector = new Connector();
    }

    public function tryToRefreshSettingsData()
    {
        $this->response = $this->connector->getHQRentalsVehicleTypes();
    }

    public function dataWasRetrieved()
    {
        return true;
    }

    public function setDataOnWP()
    {
        if (is_array($this->response)) {
            foreach ($this->response as $type) {
                $newLocation = new HQRentalsModelsVehicleType();
                $newLocation->setVehicleTypeFromApi($type);
                $newLocation->create();
            }
        }
    }

    public function getError()
    {
        return $this->response->error;
    }

    public function getResponse()
    {
        return $this->response;
    }
    public function setDataOnVehicleTypes()
    {
        if (is_array($this->response)) {
            foreach ($this->response as $type) {
                $newLocation = new HQRentalsModelsVehicleType();
                $newLocation->setVehicleTypeFromApi($type);
                $newLocation->create();
            }
        }
    }
    public function setDataWPVehicleTypes()
    {
        $this->response = $this->connector->getHQRentalsVehicleTypes();
        return $this->setDataOnWP();
    }
}
