<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector as Connector;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsCarRentalSetting;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsLocation as HQLocation;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsCarRentalSettingsTask extends HQRentalsBaseTask
{
    public function __construct()
    {
        $this->connector = new Connector();
        $this->settings = new HQRentalsSettings();
    }

    public function tryToRefreshSettingsData()
    {
        $this->response = $this->connector->getHQRentalsCarRentalSettings();
    }

    public function dataWasRetrieved()
    {
        return $this->response->success;
    }

    public function setDataOnWP()
    {
        if ($this->response->success and !empty($this->response->data)) {
            foreach ($this->response->data as $settingData) {
                $setting = new HQRentalsModelsCarRentalSetting();
                $setting->setFromApi($settingData);
                $setting->saveOrUpdate();
            }
        }
    }

    public function getError()
    {
        return $this->response->errors;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
