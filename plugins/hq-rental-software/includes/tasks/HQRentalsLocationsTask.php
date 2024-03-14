<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector as Connector;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsLocation as HQLocation;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;

class HQRentalsLocationsTask extends HQRentalsBaseTask
{
    public function __construct()
    {
        $this->connector = new Connector();
        $this->query = new HQRentalsDBQueriesLocations();
    }

    /*Get data from api and set response*/
    public function tryToRefreshSettingsData()
    {
        $this->response = $this->connector->getHQRentalsLocations();
    }


    public function dataWasRetrieved()
    {
        return $this->response->success;
    }

    public function setDataOnWP()
    {
        if ($this->response->success and !empty($this->response->data)) {
            $hqIds = $this->getCurrentSystemIds($this->response->data);
            foreach ($this->response->data as $location) {
                $newLocation = new HQLocation();
                $newLocation->setLocationFromApi($location);
                $newLocation->create();
                $newLocation->saveOrUpdate();
            }
            $dbIds = $this->currentDBIds();
            $idsToDelete = array_diff($dbIds, $hqIds);
            $this->query->deleteLocations($idsToDelete);
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
    public function getCurrentSystemIds($locations): array
    {
        if (is_array($locations)) {
            return array_map(function ($item) {
                return $item->id;
            }, $locations);
        } else {
            return $this->currentDBIds();
        }
    }
    public function currentDBIds(): array
    {
        return $this->query->getAllLocationsIds();
    }
    public function setDataWPLocations()
    {
        $this->response = $this->connector->getHQRentalsLocations();
        $this->setDataOnWP();
        return $this->response;
    }
}
