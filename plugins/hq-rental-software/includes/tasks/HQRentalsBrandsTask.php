<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector as Connector;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesBrands;

class HQRentalsBrandsTask extends HQRentalsBaseTask
{
    public function __construct()
    {
        $this->connector = new Connector();
        $this->query = new HQRentalsDBQueriesBrands();
    }

    public function tryToRefreshSettingsData()
    {
        $this->response = $this->connector->getHQRentalsBrands();
    }

    public function setDataOnWP()
    {
        if ($this->response->success and !empty($this->response->data)) {
            $hqIds = $this->getCurrentSystemIds($this->response->data);
            foreach ($this->response->data as $brand) {
                $newBrand = new HQRentalsModelsBrand();
                $newBrand->setBrandFromApi($brand);
                $newBrand->create();
                $newBrand->saveOrUpdate();
            }
            $dbIds = $this->currentDBIds();
            $idsToDelete = array_diff($dbIds, $hqIds);
            $this->query->deleteBrands($idsToDelete);
        }
    }

    public function dataWasRetrieved()
    {
        return $this->response->success;
    }

    public function getError()
    {
        return $this->response->error;
    }

    public function getResponse()
    {
        return $this->response;
    }
    public function getCurrentSystemIds($brands): array
    {
        if (is_array($brands)) {
            return array_map(function ($item) {
                return $item->id;
            }, $brands);
        } else {
            return $this->currentDBIds();
        }
    }

    public function currentDBIds()
    {
        return $this->query->getAllBrandsIds();
    }
    public function setDataWPBrands()
    {
        $this->response = $this->connector->getHQRentalsBrands();
        $this->setDataOnWP();
        return $this->response;
    }
}
