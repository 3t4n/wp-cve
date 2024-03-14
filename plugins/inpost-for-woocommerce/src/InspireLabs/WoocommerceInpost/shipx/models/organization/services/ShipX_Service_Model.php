<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\organization\services;

class ShipX_Service_Model
{
    /**
     * @desc Standard courier shipment
     */
    const SERVICE_INPOST_COURIER_STANDARD = 'inpost_courier_standard';

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
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var ShipX_Additional_Service_Model[]
     */
    private $additional_services;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return ShipX_Additional_Service_Model[]
     */
    public function getAdditionalServices()
    {
        return $this->additional_services;
    }

    /**
     * @param ShipX_Additional_Service_Model[] $additional_services
     */
    public function setAdditionalServices($additional_services)
    {
        $this->additional_services = $additional_services;
    }
}
