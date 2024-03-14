<?php

namespace HQRentalsPlugin\HQRentalsQueries;

use HQRentalsPlugin\HQRentalsDb\HQRentalsDbManager;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsCacheHandler;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsActiveRate;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClass;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsDBQueriesVehicleClasses extends HQRentalsDBBaseQueries
{
    public function __construct()
    {
        $this->model = new HQRentalsModelsVehicleClass();
        $this->db = new HQRentalsDbManager();
        $this->rateQuery = new HQRentalsQueriesActiveRates();
        $this->cache = new HQRentalsCacheHandler();
        $this->settings = new HQRentalsSettings();
        $this->rate = new HQRentalsModelsActiveRate();
    }

    public function allVehicleClasses($all = false)
    {
        if ($all) {
            $query = $this->db->selectFromTable($this->model->getTableName(), '*', '', 'ORDER BY vehicle_class_order ASC');
        } else {
            $query = $this->getVehicleByRate();
        }

        if ($query->success) {
            return $this->fillObjectsFromDB($query->data);
        }
        return [];
    }
    public function getVehicleByRate($rate = "daily_rate_amount", $force_rate = false)
    {
        return $this->db->innerJoinTable(
            $this->model->getTableName(),
            $this->rate->getTableName(),
            "id",
            "vehicle_class_id",
            ($force_rate) ? $this->rate->getTableName() . "." . $rate
                : array(
                    array(
                        'table' => $this->rate->getTableName() ,
                        'direction' => 'ASC',
                        'column' => 'daily_rate_amount'
                    ),
                    array(
                        'table' => $this->model->getTableName(),
                        'column' => 'vehicle_class_order',
                        'direction' => 'ASC'
                    )
            )
        );
    }

    public function fillObjectsFromDB($queryArray)
    {
        if (is_array($queryArray)) {
            return array_map(function ($vehicle) {
                return $this->fillObjectFromDB($vehicle);
            }, $queryArray);
        }
        return [];
    }

    public function fillObjectFromDB($vehicleFromDB)
    {
        $vehicle = new HQRentalsModelsVehicleClass();
        $vehicle->setFromDB($vehicleFromDB);
        return $vehicle;
    }

    public function getAllVehicleClassesIds(): array
    {
        $query = $this->db->selectFromTable($this->model->getTableName(), 'id', '', 'ORDER BY id');
        if ($query->success) {
            return array_map(function ($id) {
                return (int)$id->id;
            }, $query->data) ;
        }
        return [];
    }
    public function deleteVehicleClasses($ids)
    {
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $this->db->delete($this->model->getTableName(), $id);
            }
        }
        if (is_string($ids)) {
            $this->db->delete($this->model->getTableName(), $ids);
        }
    }

    public function getVehicleClassById($id)
    {
        $query = $this->db->selectFromTable($this->model->getTableName(), '*', 'id=' . $id);
        if ($query->success) {
            $result = $this->fillObjectsFromDB($query->data);
            return (is_array($result) and count($result)) ? $result[0] : new HQRentalsModelsVehicleClass();
        }
        return null;
    }
    public function getVehiclesByBrand($brandId)
    {
        $query = $this->db->selectFromTable($this->model->getTableName(), '*', 'brand_id=' . $brandId);
        if ($query->success) {
            return $this->fillObjectsFromDB($query->data);
        }
        return null;
    }
    public function getAllCustomFieldsValues(): array
    {
        $vehicles = $this->allVehicleClasses();
        $data = array();
        foreach ($vehicles as $vehicle) {
            $fields = $vehicle->getCustomFieldsAsArray();
            if (!empty($fields[$this->settings->getVehicleClassTypeField()])) {
                $data[] = $fields[$this->settings->getVehicleClassTypeField()];
            }
        }
        return array_unique($data);
    }
    public function getAllCustomFields(): array
    {
        $vehicles = $this->allVehicleClasses();
        $data = array();
        foreach ($vehicles as $vehicle) {
            $fields = $vehicle->getCustomFieldsAsArray();
            if ($fields[$this->settings->getVehicleClassTypeField()]) {
                $data[] = $fields[$this->settings->getVehicleClassTypeField()];
            }
        }
        return array_unique($data);
    }
}
