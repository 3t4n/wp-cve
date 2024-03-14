<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector as Connector;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsSettingsTask extends HQRentalsBaseTask
{
    public function __construct()
    {
        $this->connector = new Connector();
        $this->settings = new HQRentalsSettings();
    }

    public function tryToRefreshSettingsData()
    {
        $this->response = $this->connector->getHQRentalsTenantsSettings();
    }

    public function dataWasRetrieved()
    {
        return $this->response->success;
    }

    public function setDataOnWP()
    {
        if ($this->response->success) {
            $this->settings->saveTenantDatetimeOption($this->response->data->date_format);
            $this->settings->saveTenantLink($this->response->data->tenant_link);
            $this->settings->saveMetricSystem($this->response->data->metric_system);
            $this->settings->setDefaultPickupTime(
                $this->response->data->default_pick_up_time ?? ''
            );
            $this->settings->setDefaultReturnTime(
                $this->response->data->default_return_time ?? ''
            );
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
