<?php

namespace InspireLabs\WoocommerceInpost\shipx\services\organization;

use Exception;
use InspireLabs\WoocommerceInpost\shipx\models\organization\services\ShipX_Additional_Service_Model;
use InspireLabs\WoocommerceInpost\shipx\models\organization\services\ShipX_Service_Model;
use InspireLabs\WoocommerceInpost\shipx\models\organization\ShipX_Organization_Model;

class ShipX_Organization_Service {

	private $test_data
		= [
			"inpost_locker_standard",
			"inpost_courier_palette",
			"inpost_courier_standard",
			"inpost_courier_express_1000",
			"inpost_courier_express_1200",
			"inpost_courier_express_1700",
			"inpost_courier_local_standard",
			"inpost_courier_local_express",
			"inpost_courier_local_super_express",
			"inpost_locker_allegro",
			"inpost_courier_allegro",
			"inpost_letter_allegro",
			"inpost_locker_pass_thru",
		];
	/**
	 * @param int|null $id
	 *
	 * @return ShipX_Organization_Model | null
	 * @throws Exception
	 */
    public function query_organisation( $id = null ) {

        for ( $x = 0; $x <= 3; $x ++ ) {

            $organization = EasyPack_API()->get_organization( $id );
            if ( ! empty( $organization ) ) {
                update_option( 'woo_inpost_organisation', $organization );
                break;
            }
            sleep(1);
        }

        if ( ! $organization ) {
            return null;
        }

        $organization_obj = new ShipX_Organization_Model();
        $services_for_organisation = $organization['services'];

        for ( $x = 0; $x <= 3; $x ++ ) {

            $allServices = EasyPack_API()->getServicesGlobal();

            if ( ! empty( $allServices ) ) {
                update_option( 'woo_inpost_services_global', $allServices );
                break;
            }
            sleep(1);
        }

        $available_services = [];
        foreach ( $allServices as $k => $service ) {
            if ( in_array( $service['id'], $services_for_organisation ) ) {
                $service_obj = new ShipX_Service_Model();
                $service_obj->setId( $service['id'] );
                $service_obj->setName( $service['name'] );
                $service_obj->setDescription( $service['description'] );

                $additional_services = [];
                foreach ( $service['additional_services'] as $additional_service ) {
                    $additional_service_obj = new ShipX_Additional_Service_Model();
                    $additional_service_obj->setId( $additional_service['id'] );
                    $additional_service_obj->setName( $additional_service['name'] );
                    $additional_service_obj->setDescription( $additional_service['description'] );
                    $additional_services[] = $additional_service_obj;
                }
                $service_obj->setAdditionalServices( $additional_services );
                $available_services[] = $service_obj;
            }
        }

        $organization_obj->setServices( $available_services );

        return $organization_obj;
    }
}
