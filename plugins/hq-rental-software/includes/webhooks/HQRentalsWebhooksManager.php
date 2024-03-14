<?php

namespace HQRentalsPlugin\HQRentalsWebhooks;

use HQRentalsPlugin\HQRentalsActions\HQRentalsUpgrader;
use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsBrandsTask;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsLocationsTask;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsScheduler;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsVehicleClassesTask;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsVehicleTypesTask;

class HQRentalsWebhooksManager
{
    public function __construct()
    {
        $this->scheduler = new HQRentalsScheduler();
        $this->locationTask = new HQRentalsLocationsTask();
        $this->brandsTask = new HQRentalsBrandsTask();
        $this->vehiclesTask = new HQRentalsVehicleClassesTask();
        $this->typeTaks = new HQRentalsVehicleTypesTask();
        $this->upgrader = new HQRentalsUpgrader();
        $this->connector = new HQRentalsApiConnector();
        add_action('rest_api_init', array($this, 'setCustomAPIRoutes'));
    }

    public function setCustomApiRoutes()
    {
        //update plugin data - global
        //baseURl/wp-json/hqrentals/update/
        register_rest_route('hqrentals', '/update/', array(
            'methods' => 'POST',
            'callback' => array($this, 'fireUpdate'),
            'permission_callback' => function () {
                return true;
            }
        ));
        //upgrade plugin remotely
        //baseURl/wp-json/hqrentals/plugin/upgrade/
        register_rest_route('hqrentals', '/plugin/upgrade/', array(
            'methods' => 'POST',
            'callback' => array($this, 'firePluginUpgrade'),
            'permission_callback' => function () {
                return true;
            }
        ));
        // admin auth
        register_rest_route('hqrentals', '/plugin/auth', array(
            'methods' => 'GET',
            'callback' => array($this, 'loginUser'),
            'permission_callback' => function () {
                return true;
            }
        ));
        //baseURl/wp-json/hqrentals/update/locations
        register_rest_route('hqrentals', '/update/locations', array(
            'methods' => 'POST',
            'callback' => array($this, 'fireUpdateLocations'),
            'permission_callback' => function () {
                return true;
            }
        ));
        //baseURl/wp-json/hqrentals/update/brands
        register_rest_route('hqrentals', '/update/brands', array(
            'methods' => 'POST',
            'callback' => array($this, 'fireUpdateBrands'),
            'permission_callback' => function () {
                return true;
            }
        ));
        //baseURl/wp-json/hqrentals/plugin/places
        register_rest_route('hqrentals', 'plugin/places', array(
            'methods' => 'GET',
            'callback' => array($this, 'fireGetPlacesFromGoogle'),
            'permission_callback' => function () {
                return true;
            }
        ));
        //baseURl/wp-json/hqrentals/update/vehicle-classes
        register_rest_route('hqrentals', '/update/vehicle-classes', array(
            'methods' => 'POST',
            'callback' => array($this, 'fireUpdateVehicleClasses'),
            'permission_callback' => function () {
                return true;
            }
        ));
        register_rest_route('hqrentals', '/quote-form', array(
            'methods' => 'POST',
            'callback' => array($this, 'fireQuoteForm'),
            'permission_callback' => function () {
                return true;
            }
        ));
    }

    public function fireUpdate(\WP_REST_Request $request)
    {
        //Should be validated -> add Success params to the response
        $this->scheduler->refreshHQData();
        $response = new \WP_REST_Response();
        $response->status = 200;
        $response->data = HQRentalsApiResponse::resolveSuccess([
            'message' => 'Sync Completed'
        ]);
        return $response;
    }

    public function fireUpdateLocations(\WP_REST_Request $request)
    {
        $response = new \WP_REST_Response();
        $this->scheduler->deleteHQSelectedData("hqwp_locations", "hq_wordpress_location");
        return $this->resolveSingleUpdate($response, $this->locationTask->setDataWPLocations());
    }
    public function fireUpdateBrands(\WP_REST_Request $request)
    {
        $response = new \WP_REST_Response();
        $this->scheduler->deleteHQSelectedData("hqwp_brands", "hq_wordpress_brand");
        return $this->resolveSingleUpdate($response, $this->brandsTask->setDataWPBrands());
    }
    public function fireUpdateVehicleClasses(\WP_REST_Request $request)
    {
        $response = new \WP_REST_Response();

        $posts = [
            "hqwp_veh_classes" => "hq_wordpress_vehicle_class",
            "hqwp_active_rate" => "hq_wordpress_active_rate",
            "hqwp_charges" => "hq_wordpress_additional_charge",
            "hqwp_feature" => "hq_wordpress_feature",
            "hqwp_price_inter" => "hq_wordpress_price_interval",
            "hqwp_veh_charge" => "hq_wordpress_vehicle_charge",
            "hqwp_veh_cfields" => "hq_wordpress_custom_field",
            "hqwp_vehicle_image" => "hq_wordpress_vehicle_image",
            "hqwp_vehicle_types" => "hq_wordpress_vehicle_image"
        ];

        foreach ($posts as $postLike => $metaLike) {
            $this->scheduler->deleteHQSelectedData($postLike, $metaLike);
        }
        $this->typeTaks->setDataWPVehicleTypes();
        return $this->resolveSingleUpdate($response, $this->vehiclesTask->setDataWPVehicleClasses());
    }


    public function firePluginUpgrade()
    {
        $response = $this->upgrader->upgradePlugin();
        if ($response) {
            $data = $this->resolveResponse($response, 200, "Update Complete");
            $response = new \WP_REST_Response($data);
            $response->status = 200;
            return $response;
        } else {
            $data = $this->resolveResponse($response, 500, "Update Unsuccessful");
            $response = new \WP_REST_Response($data);
            $response->status = 500;
            return $response;
        }
    }

    public function resolveResponse($data, $status = 200, $message = '')
    {
        return array(
            'message' => $message,
            'status' => $status,
            'data' => empty($data) ? [] : $data
        );
    }

    public function loginUser(\WP_REST_Request $request)
    {
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $result = $this->connector->login($email, $password);
        $this->scheduler->refreshHQData();
        return $result;
    }
    private function resolveSingleUpdate($response, $updateResponse)
    {
        try {
            if ($updateResponse->success) {
                $response->status = 200;
                return $response;
            } else {
                $response->status = 500;
                return $response;
            }
        } catch (\Throwable $exception) {
            $response->status = 500;
            return $response;
        }
    }
    public function fireGetPlacesFromGoogle()
    {
        $place = $_GET['search'];
        $connector = new HQRentalsApiConnector();
        $response = $connector->getGooglePlacesOnAutocomplete($place);
        return $response;
    }
    public function fireQuoteForm(\WP_REST_Request $data)
    {
        $connector = new HQRentalsApiConnector();
        $response = $connector->tryToSendAQuote($data->get_params());
        return $response;
    }
}
