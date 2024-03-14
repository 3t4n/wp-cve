<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\courier_pickup;

class ShipX_Dispatch_Order_Point_Address_Model
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $street;

    /**
     * @var int
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return int
     */
    public function getBuildingNumber()
    {
        return $this->building_number;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->post_code;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @param int $building_number
     */
    public function setBuildingNumber($building_number)
    {
        $this->building_number = $building_number;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @param string $post_code
     */
    public function setPostCode($post_code)
    {
        $this->post_code = $post_code;
    }

    /**
     * @param string $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
    }
}
