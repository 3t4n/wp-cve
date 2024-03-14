<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\organization\services;

class ShipX_Additional_Service_Model
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
     * @desc This is additional service for Standard courier shipment with extra charge.
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
}
