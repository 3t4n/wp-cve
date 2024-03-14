<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector as Connector;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsAdditionalCharge as HQCharge;

class HQRentalsAdditionalChargesTask extends HQRentalsBaseTask
{
    public function __construct()
    {
        $this->connector = new Connector();
    }

    /*Get data from api and set response*/
    public function tryToRefreshSettingsData()
    {
        $this->response = $this->connector->getHQRentalsAdditionalCharges();
    }

    /*Validate that the response have no errors*/
    public function dataWasRetrieved()
    {
        return $this->response->success;
    }

    /*Populate WP Database*/
    public function setDataOnWP()
    {
        if ($this->response->success and !empty($this->response->data)) {
            foreach ($this->response->data as $additionalCharge) {
                $newCharge = new HQCharge();
                $newCharge->setAdditionalChargeFromApi($additionalCharge);
                $newCharge->create();
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
}
