<?php
namespace InspireLabs\WoocommerceInpost\shipx\services\shipment;

use Automattic\WooCommerce\Utilities\OrderUtil;
use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_API;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Cod_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Contstants;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Custom_Attributes_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Insurance_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Internal_Data;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Dimensions_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Weight_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Receiver_Address_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Receiver_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Sender_Address_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Sender_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Status_History_Item_Model;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;


class ShipX_Shipment_Service
{

    /**
     * @param ShipX_Shipment_Model $shipment
     */
    public function update_shipment_to_db(ShipX_Shipment_Model $shipment)
    {
	    if (  null === $shipment->getInternalData()->getLastStatusFromHistory()
	          || $shipment->getInternalData()->getStatus()
	             !== $shipment->getInternalData()->getLastStatusFromHistory()->get_name()
	    ) {

		    $statusHistoryItem = new ShipX_Shipment_Status_History_Item_Model();
		    $statusHistoryItem->set_name( $shipment->getInternalData()->getStatus() );
		    $statusHistoryItem->set_timestamp( $shipment->getInternalData()->getStatusChangedTimestamp() );
		    $shipment->getInternalData()->putStatusHistoryItem( $statusHistoryItem );
	    }

        update_post_meta($shipment->getInternalData()->getOrderId(), '_shipx_shipment_object', $shipment);
		
		if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
            $order = wc_get_order( $shipment->getInternalData()->getOrderId() );
            if ( $order && !is_wp_error($order) ) {
                $order->update_meta_data( '_shipx_shipment_object', $shipment );
                $order->save();
            }
        }
    }

    /**
     * @param $order_id
     *
     * @return ShipX_Shipment_Model|null
     */
    public function get_shipment_by_order_id($order_id)
    {
        $order = wc_get_order( $order_id );
        $from_order_meta = null;

        //if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
        if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
            // HPOS usage is enabled.
            $from_order_meta_raw = isset( get_post_meta( $order_id )['_shipx_shipment_object'][0] )
                ? get_post_meta( $order_id )['_shipx_shipment_object'][0]
                : '';

            if( !empty( $from_order_meta_raw ) ) {
                $from_order_meta = unserialize( $from_order_meta_raw );
            }

        } else {
            // Traditional orders are in use.
            $from_order_meta = $order->get_meta('_shipx_shipment_object');

        }

        return $from_order_meta instanceof ShipX_Shipment_Model
            ? $from_order_meta
            : null;
    }

    /**
     * @param array       $parcels
     * @param int         $order_id
     * @param string      $send_method
     * @param string      $service
     * @param array       $sizes
     *
     * @param string      $parcel_machine_id
     *
     * @param null        $cod_amount
     *
     * @param float|null  $insurance_amount
     *
     * @param string|null $reference_number
     *
     * @param string|null $commercial_product_identifier
     *
     * @return ShipX_Shipment_Model
     */
    public function create_shipment_object_by_shiping_data(
        $parcels,
	    $order_id,
	    $send_method,
	    $service,
	    $sizes = [],
	    $parcel_machine_id = null,
	    $cod_amount = null,
	    $insurance_amount = null,
	    $reference_number = null,
        $commercial_product_identifier = null
    ) {

        $is_service_courier_type = $this->is_service_id_courier_type($service);

        $insurance_amount = floatval($insurance_amount);

        if ($insurance_amount <= 0) {
            $insurance_amount = null;
        }

        $shipment = new ShipX_Shipment_Model();
		$shipment->setExternalCustomerid('woocommerce');
        $additional_services = [];
        $order = wc_get_order($order_id);
        $customAttributes = new ShipX_Shipment_Custom_Attributes_Model();

        if (false === $is_service_courier_type) {
            $customAttributes->setTargetPoint($parcel_machine_id);
        }

        switch ($send_method) {
            case 'parcel_machine':
                //$customAttributes->setDropoffPoint(get_option('easypack_default_machine_id'));
                $customAttributes->setDropoffPoint(null);
                $customAttributes->setSendingMethod(ShipX_Shipment_Custom_Attributes_Model::SENDING_METHOD_PARCEL_LOCKER);
                break;

            case 'courier':
                $customAttributes->setSendingMethod(ShipX_Shipment_Custom_Attributes_Model::SENDING_METHOD_DISPATCH_ORDER);
                break;

            case 'pop':
                $customAttributes->setSendingMethod(ShipX_Shipment_Custom_Attributes_Model::SENDING_METHOD_POP);
                break;
        }

        $shipment->setCustomAttributes($customAttributes);
        $receiver = new ShipX_Shipment_Receiver_Model();
        $receiver->setFirstName($order->get_shipping_first_name());
        $receiver->setLastName($order->get_shipping_last_name());
        $receiver->setEmail($order->get_billing_email());
        //$receiver->setPhone($order->get_billing_phone());
        $receiver->setPhone( preg_replace('/^\+48|\D/', '', ( trim( $order->get_billing_phone() ) ) ) );

        $receiverAddress = new ShipX_Shipment_Receiver_Address_Model();

        if (!empty($order->get_shipping_address_1())) {
            $receiverAddress->setStreet($order->get_shipping_address_1());
            if (empty($order->get_shipping_address_2())) {
                $receiverAddress->setBuildingNumber( ' . ' ); // avoid duplicated lines "street" in address and error from API
            } else {
                $receiverAddress->setBuildingNumber($order->get_shipping_address_2());
            }
            $receiver->setCompanyName($order->get_shipping_company());
            $receiverAddress->setPostCode($order->get_shipping_postcode());
            $receiverAddress->setCity($order->get_shipping_city());
            $receiverAddress->setCountryCode($order->get_shipping_country());
        } else {
            $receiverAddress->setStreet($order->get_billing_address_1());
            if (empty($order->get_shipping_address_2())) {
                $receiverAddress->setBuildingNumber($order->get_billing_address_1());
            } else {
                $receiverAddress->setBuildingNumber($order->get_billing_address_2());
            }
            $receiver->setCompanyName($order->get_billing_company());
            $receiverAddress->setPostCode($order->get_shipping_postcode());
            $receiverAddress->setCity($order->get_shipping_city());
            $receiverAddress->setCountryCode($order->get_shipping_country());
        }

        $receiver->setAddress($receiverAddress);
        $shipment->setReceiver($receiver);
        $sender = new ShipX_Shipment_Sender_Model();
        $sender->setFirstName(get_option('easypack_sender_first_name'));
        $sender->setLastName(get_option('easypack_sender_last_name'));
        $sender->setEmail(get_option('easypack_sender_email'));
        $sender->setPhone(get_option('easypack_sender_phone'));
        $sender->setCompanyName(get_option('easypack_sender_company_name'));
        $senderAddress = new ShipX_Shipment_Sender_Address_Model();
        $senderAddress->setCountryCode(EasyPack_API()->api_country());
        $senderAddress->setCity(get_option('easypack_sender_city'));
        $senderAddress->setStreet(get_option('easypack_sender_street'));
        $senderAddress->setBuildingNumber(get_option('easypack_sender_building_no'));
        $senderAddress->setPostCode(get_option('easypack_sender_post_code'));
        $sender->setAddress($senderAddress);
        $shipment->setSender($sender);
        $internalData = new ShipX_Shipment_Internal_Data();
        $internalData->setStatus('new');

        if (EasyPack_API()->is_production_env()) {
            $internalData->setApiVersion($internalData::API_VERSION_PRODUCTION);
        } else {
            $internalData->setApiVersion($internalData::API_VERSION_SANDBOX);
        }

        $shipment->setInternalData($internalData);


        $shipment->setService($service);

        if (null !== $cod_amount && floatval($cod_amount) > 0 ) {
            //$additional_services[] = $shipment::ADDITIONAL_SERVICES_COD;
            $cod = new ShipX_Shipment_Cod_Model();
            $cod->setCurrency(ShipX_Shipment_Contstants::CURRENCY_PLN);
            $cod->setAmount((float)$cod_amount);
            $shipment->setCod($cod);
        }

        if (null !== $insurance_amount) {
            $insurance = new ShipX_Shipment_Insurance_Model();
            $insurance->setCurrency(ShipX_Shipment_Contstants::CURRENCY_PLN);
            $insurance->setAmount((float)$insurance_amount);
            $shipment->setInsurance($insurance);
        }

        $shipment->setReference($reference_number);
        $shipment->setCommercialProductIdentifier($commercial_product_identifier); // commercial_product_identifier

        $parcelsCollection = [];


        if (true === $is_service_courier_type) {
            $parcel = new ShipX_Shipment_Parcel_Model();
            $parcel->setIsNonstandard(false);
            $parcel->setId($order_id.'_1');
            $dimensions = new ShipX_Shipment_Parcel_Dimensions_Model();
            $dimensions->setUnit('mm');
            $dimensions->setLength($sizes['length']);
            $dimensions->setWidth($sizes['width']);
            $dimensions->setHeight($sizes['height']);
            $parcel->setDimensions($dimensions);
            $weight = new ShipX_Shipment_Parcel_Weight_Model();
            $weight->setUnit('kg');
            $weight->setAmount($sizes['weight']);
            $parcel->setWeight($weight);
            if ($sizes['non_standard'] === 'yes') {
                $non_standard = true;
            } else {
                $non_standard = false;
            }
            $parcel->setIsNonstandard($non_standard);
            $parcelsCollection[] = $parcel;
        } else {

            foreach ($parcels as $counter_id => $p) {
                $parcel = new ShipX_Shipment_Parcel_Model();
                $parcel->setId($order_id.'_'.$counter_id);
                $parcel->setIsNonstandard(false);
                // if parcel dimension (A,B,C) was changed via order update button
                $p = isset( $p['package_size'] ) ? $p['package_size'] : $p;

                switch ($p) {
                    case $parcel::SIZE_TEMPLATE_SMALL:
                        $parcel->setTemplate($parcel::SIZE_TEMPLATE_SMALL);
                        break;

                    case $parcel::SIZE_TEMPLATE_MEDIUM:
                        $parcel->setTemplate($parcel::SIZE_TEMPLATE_MEDIUM);
                        break;

                    case $parcel::SIZE_TEMPLATE_LARGE:
                        $parcel->setTemplate($parcel::SIZE_TEMPLATE_LARGE);
                        break;

                    case $parcel::SIZE_TEMPLATE_XLARGE:
                        $parcel->setTemplate($parcel::SIZE_TEMPLATE_XLARGE);
                        break;
                }


                $parcelsCollection[] = $parcel;
            }
        }

        if (!empty($parcelsCollection)) {
            $shipment->setParcels($parcelsCollection);
        }

        if (!empty($additional_services)) {
            $shipment->setAdditionalServices($additional_services);
        }

        return $shipment;
    }


    /**
     * @param ShipX_Shipment_Model $shipX_Shipment_Model
     *
     * @return array
     *
     * @throws ReflectionException
     */
    public function shipment_to_array($shipX_Shipment_Model)
    {

        $refl = new ReflectionClass($shipX_Shipment_Model);

        $temp = array_map(function ($prop) use ($shipX_Shipment_Model) {
            /**
             * @var ReflectionProperty $prop
             */
            $prop->setAccessible(true);

            if ('shop_data' === $prop->getName()) {
                return null;
            }

            $p = $prop->getValue($shipX_Shipment_Model);
            if (is_object($p)) {
                return [
                    $prop->getName(),
                    $this->shipment_to_array($prop->getValue($shipX_Shipment_Model)),
                ];
            } else {
                return [
                    $prop->getName(),
                    $prop->getValue($shipX_Shipment_Model),
                ];
            }

        }, $refl->getProperties());

        foreach ($temp as $key => $property) {

            if (null === $property) {
                unset($temp[$key]);
                continue;
            }

            $kname = $property[0];
            unset($temp[$key]);
            $temp[$kname] = $property[1];

            if ($kname === 'parcels') {
                foreach ($property[1] as $k => $parcel) {
                    $parcels[] = $this->shipment_to_array($parcel);
                }
                $temp[$kname] = $parcels;
            }

        }

        return $temp;

    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return bool
     */
    public function is_courier_service($shipment)
    {

        if (in_array($shipment->getService()
            , [
                $shipment::SERVICE_INPOST_COURIER_LOCAL_SUPER_EXPRESS,
                $shipment::SERVICE_INPOST_COURIER_LOCAL_STANDARD,
                $shipment::SERVICE_INPOST_COURIER_EXPRESS_1700,
                $shipment::SERVICE_INPOST_COURIER_EXPRESS_1200,
                $shipment::SERVICE_INPOST_COURIER_EXPRESS_1000,
                $shipment::SERVICE_INPOST_COURIER_STANDARD,
                $shipment::SERVICE_INPOST_COURIER_PALETTE,
                $shipment::SERVICE_INPOST_COURIER_ALLEGRO,
                $shipment::SERVICE_INPOST_COURIER_LOCAL_EXPRESS,
				$shipment::SERVICE_INPOST_COURIER_C2C,
                $shipment::SERVICE_INPOST_COURIER_C2C_COD,
            ])
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $method_id
     *
     * @return bool
     */
    public function is_service_id_courier_type($method_id)
    {
        if (in_array($method_id
            , [
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_LOCAL_SUPER_EXPRESS,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_LOCAL_STANDARD,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_EXPRESS_1700,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_EXPRESS_1200,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_EXPRESS_1000,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_STANDARD,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_PALETTE,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_ALLEGRO,
                ShipX_Shipment_Model::SERVICE_INPOST_COURIER_LOCAL_EXPRESS,
            ])
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return bool
     */
    public function is_courier_sending_method($shipment)
    {
        if ($shipment::SENDING_METHOD_DISPATCH_ORDER
            === $shipment->getCustomAttributes()->getSendingMethod()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return bool
     */
    public function is_pop_sending_method($shipment)
    {
        if ($shipment::SENDING_METHOD_POP ===
            $shipment->getCustomAttributes()->getSendingMethod()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return bool
     */
    public function is_parcel_locker_sending_method($shipment)
    {
        if ($shipment::SENDING_METHOD_PARCEL_LOCKER
            === $shipment->getCustomAttributes()->getSendingMethod()
        ) {
            return true;
        }

        return false;
    }


    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return bool
     */
    public function getTrackingUrl($shipment)
    {
        if (EasyPack_API::COUNTRY_PL === EasyPack_API()->getCountry()) {
            return sprintf('https://inpost.pl/sledzenie-przesylek?number=%s',
                $shipment->getInternalData()->getTrackingNumber()
            );
        }

        return ''; //todo gb
    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return bool
     */
    public function is_shipment_match_to_current_api(
        ShipX_Shipment_Model $shipment
    ) {
        $internal_data = $shipment->getInternalData();
        $shipment_api = $internal_data->getApiVersion();

        if ($internal_data::API_VERSION_PRODUCTION === $shipment_api) {
            if (EasyPack_API()->is_production_env()) {
                return true;
            } else {
                return false;
            }
        }

        if ($internal_data::API_VERSION_SANDBOX === $shipment_api) {
            if (EasyPack_API()->is_sandbox_env()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return bool
     */
    public function is_shipment_cancellable(ShipX_Shipment_Model $shipment)
    {
        $shipment_status = $shipment->getInternalData()->getStatus();
        if ('created' === $shipment_status
            || 'offers_prepared' === $shipment_status
        ) {

            return true;
        } else {

            return false;
        }
    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return string
     */
    public function get_customer_service_name(ShipX_Shipment_Model $shipment)
    {
        $service_id = $shipment->getService();

        return $this->get_customer_service_name_by_id($service_id);
    }

    /**
     * @param string $service_id
     *
     * @return string
     */
    public function get_customer_service_name_by_id($service_id)
    {
        switch ($service_id) {
            case ShipX_Shipment_Model::SERVICE_INPOST_LETTER_ECOMMERCE:
                return __('Parcel e-commerce InPost', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_LOCKER_STANDARD:
                return __('Standard parcel locker shipment', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_LOCKER_PASS_THRU:
                return __('PassThru parcel (no logistics)', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_LOCKER_ALLEGRO:
                return __('Allegro InPost Parcel Lockers shipment', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_LETTER_ALLEGRO:
                return __('Allegro InPost Registered Mail shipment', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_ALLEGRO:
                return __('Allegro InPost Courier shipment', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_PALETTE:
                return __('Standard Pallet courier shipment', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_STANDARD:
                return __('Standard courier shipment', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_EXPRESS_1000:
                return __('Courier shipment with delivery to 10 a.m. on the following day', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_EXPRESS_1200:
                return __('Courier shipment with delivery to 12 p.m. on the following day', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_EXPRESS_1700:
                return __('Courier shipment with delivery to 5 p.m. on the following day', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_LOCAL_STANDARD:
                return __('Standard local courier service', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_LOCAL_EXPRESS:
                return __('Express local courier service', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_LOCAL_SUPER_EXPRESS:
                return __('Super Express local courier service', 'woocommerce-inpost');
			case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_C2C:
                return __('InPost Courier C2C', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_COURIER_C2C_COD:
                return __('InPost Courier C2C COD', 'woocommerce-inpost');
            case ShipX_Shipment_Model::SERVICE_INPOST_LOCKER_ECONOMY:
                return __('InPost Locker Economy', 'woocommerce-inpost');
        }

        return __('Unknown service', 'woocommerce-inpost');
    }

    /**
     * @return array
     */
    public function get_services_key_value()
    {
        $services[] = 'inpost_courier_standard';
        //$services[] = 'inpost_courier_express_1000';
        //$services[] = 'inpost_courier_express_1200';
        //services[] = 'inpost_courier_express_1700';
        $services[] = 'inpost_courier_palette';
        $services[] = 'inpost_courier_local_standard';
        $services[] = 'inpost_courier_local_express';
        $services[] = 'inpost_courier_local_super_express';
        $services[] = 'inpost_locker_standard';
        $services[] = 'inpost_courier_c2c';
        $services[] = 'inpost_courier_c2c_cod';
        //$services[] = 'inpost_locker_allegro';
        //$services[] = 'inpost_letter_ecommerce';
        //$services[] = 'inpost_courier_allegro';

        $return = [
            'any' => __('Any service', 'woocommerce-inpost'),
        ];
        foreach ($services as $id) {
            $return[$id] = $this->get_customer_service_name_by_id( $id );
        }

        return $return;
    }

    /**
     * @param ShipX_Shipment_Model $shipment
     *
     * @return null|string
     */
    public function get_table_attributes(ShipX_Shipment_Model $shipment)
    {
        $parcels = $shipment->getParcels();
        foreach ($parcels as $parcel) {
            if ($this->is_courier_service($shipment)) {

                $dimensions = $parcel->getDimensions();
                $weight = $parcel->getWeight();
                if (null === $dimensions || null === $weight) {
                    return null;
                }
                $weight_unit = $weight->getUnit();
                $dim_unit = $dimensions->getUnit();
                $length = sprintf('%s: %s %s',
                    __('Length', 'woocommerce-inpost'),
                    $dimensions->getLength(),
                    $dim_unit);
                $width = sprintf('%s: %s %s',
                    __('Width', 'woocommerce-inpost'),
                    $dimensions->getWidth(),
                    $dim_unit);
                $height = sprintf('%s: %s %s',
                    __('Height', 'woocommerce-inpost'),
                    $dimensions->getHeight(),
                    $dim_unit);
                $weight = sprintf('%s: %s %s',
                    __('Weight', 'woocommerce-inpost'),
                    $weight->getAmount(),
                    $weight_unit);
                $non_standard = sprintf('%s: %s',
                    __('Non standard', 'woocommerce-inpost'),
                    ($parcel->is_non_standard() === true
                        ? __('yes', 'woocommerce-inpost')
                        : __('no', 'woocommerce-inpost'))
                );

                return sprintf('%s <br> %s <br> %s <br> %s <br> %s',
                    $length, $width, $height, $weight, $non_standard);
            }
            $size = $parcel->getTemplate();

            return $size;
        }

        return null;
    }
}
