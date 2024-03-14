<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

class ShipX_Shipment_Receiver_Address_Model
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $building_number;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $post_code;

    /**
     * @var string
     */
    private $country_code;

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
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->building_number;
    }

    /**
     * @param string $building_number
     */
    public function setBuildingNumber($building_number)
    {
        $this->building_number = $building_number;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->post_code;
    }

    /**
     * @param string $post_code
     */
    public function setPostCode($post_code)
    {
        $this->post_code = $post_code;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
    }
}
