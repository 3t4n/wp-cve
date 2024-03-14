<?php

namespace HQRentalsPlugin\HQRentalsQueries;

use HQRentalsPlugin\HQRentalsDb\HQRentalsDbManager;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDatesHelper;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsCarRentalSetting;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsLocation;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsVendor\Carbon;

class HQRentalsDBQueriesCarRentalSetting extends HQRentalsDBBaseQueries
{
    public static $settingTimeHQ = "H:i";

    public function __construct()
    {
        $this->model = new HQRentalsModelsCarRentalSetting();
        $this->db = new HQRentalsDbManager();
    }

    public function getCarRentalSetting($setting)
    {
        $query = $this->db->selectFromTable($this->model->getTableName(), '*', 'preference="' . $setting . '"');
        if ($query->success) {
            if (is_array($query->data) and count($query->data)) {
                $set = new HQRentalsModelsCarRentalSetting();
                $set->setFromDB($query->data[0]);
                return $set;
            }
            return new HQRentalsModelsCarRentalSetting();
        }
        return new HQRentalsModelsCarRentalSetting();
    }
    public function fillObjectsFromDB()
    {
    }
}
