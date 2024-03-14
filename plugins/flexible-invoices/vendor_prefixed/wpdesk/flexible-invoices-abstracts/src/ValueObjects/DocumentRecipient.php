<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
/**
 * Define document recipient.
 *
 * @package WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects
 */
class DocumentRecipient implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $street;
    /**
     * @var string
     */
    private $street2;
    /**
     * @var string
     */
    private $postcode;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $vat_number;
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $state;
    /**
     * @param string $name
     * @param string $street
     * @param string $postcode
     * @param string $city
     * @param string $vat_number
     * @param string $country
     * @param string $phone
     * @param string $email
     * @param string $street2
     * @param string $state
     */
    public function __construct($name, $street, $postcode, $city, $vat_number, $country, $phone, $email, $street2 = '', $state = '')
    {
        $this->name = $name;
        $this->street = $street;
        $this->postcode = $postcode;
        $this->city = $city;
        $this->vat_number = $vat_number;
        $this->country = $country;
        $this->phone = $phone;
        $this->email = $email;
        $this->street2 = $street2;
        $this->state = $state;
    }
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function get_street()
    {
        return $this->street;
    }
    /**
     * @return string
     */
    public function get_street2()
    {
        return $this->street2;
    }
    /**
     * @return string
     */
    public function get_postcode()
    {
        return $this->postcode;
    }
    /**
     * @return string
     */
    public function get_city()
    {
        return $this->city;
    }
    /**
     * @return string
     */
    public function get_vat_number()
    {
        return $this->vat_number;
    }
    /**
     * @return string
     */
    public function get_country()
    {
        return $this->country;
    }
    /**
     * @return string
     */
    public function get_phone()
    {
        return $this->phone;
    }
    /**
     * @return string
     */
    public function get_email()
    {
        return $this->email;
    }
    /**
     * @return string
     */
    public function get_state()
    {
        return $this->state;
    }
}
