<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

class ShipX_Shipment_Model
{

    /**
     * @desc Shipment insurance.
     */
    const ADDITIONAL_SERVICES_INSURANCE = 'insurance';

    /**
     * @desc Cash on delivery for the shipment.
     */
    const ADDITIONAL_SERVICES_COD = 'cod';

    /**
     * @desc Notification about the shipment via SMS.
     */
    const ADDITIONAL_SERVICES_SMS = 'sms';

    /**
     * @desc Notification about the shipment vie email.
     */
    const ADDITIONAL_SERVICES_EMAIL = 'email';

    /**
     * @desc Shipment delivery on Saturday.
     */
    const ADDITIONAL_SERVICES_SATURDAY = 'saturday';

    /**
     * @desc Client can clarify hour of delivery in every city in Poland.
     * @desc This is additional service for Standard courier shipment with
     *       extra charge.
     */
    const ADDITIONAL_SERVICES_DOR1720 = 'dor1720';

    /**
     * @desc Return of Documents
     */
    const ADDITIONAL_SERVICES_ROD = 'rod';

    const ADDITIONAL_SERVICES_FORHOUR_9 = 'forhour_9';

    const ADDITIONAL_SERVICES_FORHOUR_10 = 'forhour_10';

    const ADDITIONAL_SERVICES_FORHOUR_11 = 'forhour_11';

    const ADDITIONAL_SERVICES_FORHOUR_12 = 'forhour_12';

    const ADDITIONAL_SERVICES_FORHOUR_13 = 'forhour_13';

    const ADDITIONAL_SERVICES_FORHOUR_14 = 'forhour_14';

    const ADDITIONAL_SERVICES_FORHOUR_15 = 'forhour_15';

    const ADDITIONAL_SERVICES_FORHOUR_16 = 'forhour_17';

    const SENDING_METHOD_DISPATCH_ORDER = 'dispatch_order';

    const SENDING_METHOD_PARCEL_LOCKER = 'parcel_locker';

    const SENDING_METHOD_POP = 'pop';

    /**
     * @desc Weekend parcel locker shipment.
     */
    const SERVICE_INPOST_LOCKER_WEEKEND = 'inpost_locker_standard';
    const SERVICE_INPOST_LOCKER_ECONOMY = 'inpost_locker_economy';

    /**
     * @desc Standard courier shipment
     */
    const SERVICE_INPOST_COURIER_STANDARD = 'inpost_courier_standard';

	/**
     * @desc Standard courier shipment
     */
    const SERVICE_INPOST_COURIER_C2C = 'inpost_courier_c2c';
    const SERVICE_INPOST_COURIER_C2C_COD = 'inpost_courier_c2c_cod';

    /**
     * @desc Courier shipment with delivery to 10:00 a.m.
     */
    const SERVICE_INPOST_COURIER_EXPRESS_1000 = 'inpost_courier_express_1000';

    /**
     * @desc Courier shipment with delivery to 12:00 p.m.
     */
    const SERVICE_INPOST_COURIER_EXPRESS_1200 = 'inpost_courier_express_1200';

    /**
     * @desc Courier shipment with delivery to 17:00 a.m.
     */
    const SERVICE_INPOST_COURIER_EXPRESS_1700 = 'inpost_courier_express_1700';

    /**
     * @desc Standard Pallet courier shipment
     */
    const SERVICE_INPOST_COURIER_PALETTE = 'inpost_courier_palette';

    /**
     * @desc Local standard courier shipment
     */
    const SERVICE_INPOST_COURIER_LOCAL_STANDARD = 'inpost_courier_local_standard';

    /**
     * @desc  Express local courier service
     */
    const SERVICE_INPOST_COURIER_LOCAL_EXPRESS = 'inpost_courier_local_express';

    /**
     * @desc Local express courier shipment
     */
    const SERVICE_INPOST_COURIER_LOCAL_SUPER_EXPRESS = 'inpost_courier_local_super_express';

    /**
     * @desc Standard parcel locker shipment.
     */
    const SERVICE_INPOST_LOCKER_STANDARD = 'inpost_locker_standard';

    /**
     * @desc Allegro InPost Parcel Lockers shipment.
     */
    const SERVICE_INPOST_LOCKER_ALLEGRO = 'inpost_locker_allegro';

    /**
     * @desc Pass Thru locker shipment
     */
    const SERVICE_INPOST_LOCKER_PASS_THRU = 'inpost_locker_pass_thru';

    /**
     * @desc Allegro InPost Registered Mail shipment.
     */
    const SERVICE_INPOST_LETTER_ALLEGRO = 'inpost_letter_allegro';

    /**
     * @desc Parcel e-commerce InPost
     */
    const SERVICE_INPOST_LETTER_ECOMMERCE = 'inpost_letter_ecommerce';

    /**
     * @desc Allegro InPost Courier shipment
     */
    const SERVICE_INPOST_COURIER_ALLEGRO = 'inpost_courier_allegro';

    /**
     * @var ShipX_Shipment_Receiver_Model
     */
    private $receiver;

    /**
     * @var ShipX_Shipment_Sender_Model
     */
    private $sender;

    /**
     * @var ShipX_Shipment_Parcel_Model[]
     */
    private $parcels;

    /**
     * @var ShipX_Shipment_Custom_Attributes_Model
     */
    private $custom_attributes;

    /**
     * @var ShipX_Shipment_Cod_Model
     */
    private $cod;

    /**
     * @var ShipX_Shipment_Insurance_Model
     */
    private $insurance;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var bool
     */
    private $isReturn;

    /**
     * @var string
     */
    private $service;

    /**
     * @var array
     */
    private $additional_services;

    /**
     * @var string
     */
    private $external_customer_id;

    /**
     * @var bool
     */
    private $only_choice_of_offer;

    /**
     * @var ShipX_Shipment_Internal_Data
     */
    private $internal_data;

    /**
     * @var string
     */
    private $commercial_product_identifier;

    /**
     * @return ShipX_Shipment_Receiver_Model
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param ShipX_Shipment_Receiver_Model $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return ShipX_Shipment_Sender_Model
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param ShipX_Shipment_Sender_Model $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return ShipX_Shipment_Parcel_Model[]
     */
    public function getParcels()
    {
        return $this->parcels;
    }

    /**
     * @param ShipX_Shipment_Parcel_Model[] $parcels
     */
    public function setParcels($parcels)
    {
        $this->parcels = $parcels;
    }

    /**
     * @return ShipX_Shipment_Custom_Attributes_Model
     */
    public function getCustomAttributes()
    {
        return $this->custom_attributes;
    }

    /**
     * @param ShipX_Shipment_Custom_Attributes_Model $custom_attributes
     */
    public function setCustomAttributes($custom_attributes)
    {
        $this->custom_attributes = $custom_attributes;
    }

    /**
     * @return ShipX_Shipment_Cod_Model
     */
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * @param ShipX_Shipment_Cod_Model $cod
     */
    public function setCod($cod)
    {
        $this->cod = $cod;
    }

    /**
     * @return ShipX_Shipment_Insurance_Model
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * @param ShipX_Shipment_Insurance_Model $insurance
     */
    public function setInsurance($insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return bool
     */
    public function isReturn()
    {
        return $this->isReturn;
    }

    /**
     * @param bool $isReturn
     */
    public function setIsReturn($isReturn)
    {
        $this->isReturn = $isReturn;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function getAdditionalServices()
    {
        return $this->additional_services;
    }

    /**
     * @param array $additional_services
     */
    public function setAdditionalServices($additional_services)
    {
        $this->additional_services = $additional_services;
    }

    /**
     * @return string
     */
    public function getExternalCustomerid()
    {
        return $this->external_customer_id;
    }

    /**
     * @param string $external_customer_id
     */
    public function setExternalCustomerid($external_customer_id)
    {
        $this->external_customer_id = $external_customer_id;
    }

    /**
     * @return bool
     */
    public function isOnlyChoiceofoffer()
    {
        return $this->only_choice_of_offer;
    }

    /**
     * @param bool $only_choice_of_offer
     */
    public function setOnlyChoiceofoffer($only_choice_of_offer)
    {
        $this->only_choice_of_offer = $only_choice_of_offer;
    }

    /**
     * @return ShipX_Shipment_Internal_Data
     */
    public function getInternalData()
    {
        return $this->internal_data;
    }

    /**
     * @param ShipX_Shipment_Internal_Data $internal_data
     */
    public function setInternalData($internal_data)
    {
        $this->internal_data = $internal_data;
    }

    /**
     * @return bool
     */
    public function isCourier()
    {
        if (self::SENDING_METHOD_DISPATCH_ORDER === $this->getCustomAttributes()
                ->getSendingMethod()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isParcelMachine()
    {
        if (self::SENDING_METHOD_PARCEL_LOCKER === $this->getCustomAttributes()
                ->getSendingMethod()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCommercialProductIdentifier()
    {
        return $this->commercial_product_identifier;
    }

    /**
     * @param string $commercial_product_identifier
     */
    public function setCommercialProductIdentifier($commercial_product_identifier)
    {
        $this->commercial_product_identifier = $commercial_product_identifier;
    }
}
