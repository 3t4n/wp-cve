<?php

namespace HQRentalsPlugin\HQRentalsTasks;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector as Connector;
use HQRentalsPlugin\HQRentalsDb\HQRentalsDbBootstrapper;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClass as HQVehicleClass;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;

class HQRentalsVehicleClassesTask extends HQRentalsBaseTask
{
    public function __construct()
    {
        $this->connector = new Connector();
        $this->db = new HQRentalsDbBootstrapper();
        $this->query = new HQRentalsDBQueriesVehicleClasses();
    }

    public function tryToRefreshSettingsData()
    {
        $this->response = $this->connector->getHQRentalsVehicleClasses();
    }

    public function dataWasRetrieved()
    {
        return $this->response->success;
    }

    public function setDataOnWP()
    {
        $customFields = $this->connector->getHQVehicleClassCustomFields();
        if ($customFields->success) {
            foreach ($customFields->data as $field) {
                HQVehicleClass::$custom_fields[] = $field->dbcolumn;
            }
        }
        $fields = HQVehicleClass::$custom_fields;
        $this->db->createColumnsForVehiclesClassesCustomFields($fields);
        if ($this->response->success and !empty($this->response->data)) {
            $hqIds = $this->getCurrentSystemIds($this->response->data);
            foreach ($this->response->data as $vehicle_class) {
                $newVehicleClass = new HQVehicleClass();
                $newVehicleClass->setVehicleClassFromApi($vehicle_class, $customFields);
                $newVehicleClass->create();
                $newVehicleClass->saveOrUpdate();
            }
            $dbIds = $this->currentDBIds();
            $idsToDelete = array_diff($dbIds, $hqIds);
            $this->query->deleteVehicleClasses($idsToDelete);
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

    public function getCurrentSystemIds($vehicles): array
    {
        if (is_array($vehicles)) {
            return array_map(function ($item) {
                return $item->id;
            }, $vehicles);
        } else {
            return $this->currentDBIds();
        }
    }
    public function currentDBIds(): array
    {
        return $this->query->getAllVehicleClassesIds();
    }
    public function setDataWPVehicleClasses()
    {
        $this->response = $this->connector->getHQRentalsVehicleClasses();
        $this->setDataOnWP();
        return $this->response;
    }
}
