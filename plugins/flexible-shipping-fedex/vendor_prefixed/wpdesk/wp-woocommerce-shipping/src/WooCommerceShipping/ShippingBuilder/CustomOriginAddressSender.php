<?php

/**
 * WooCommerce shipping address: CustomOriginAddressSender.
 *
 * @package WPDesk\ShippingBuilder\Address
 */
namespace FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder;

use FedExVendor\WPDesk\AbstractShipping\Shipment\Address;
/**
 * Get custon sender address.
 *
 * @package WPDesk\ShippingBuilder\Address
 */
class CustomOriginAddressSender implements \FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\AddressProvider
{
    /**
     * @var Address;
     */
    private $sender_address;
    /**
     * CustomOriginAddressSender constructor.
     *
     * @param string $address_line1
     * @param string $address_line2
     * @param string $city
     * @param string $postal_code
     * @param string $country_code
     * @param string $state_code
     */
    public function __construct($address_line1, $address_line2, $city, $postal_code, $country_code, $state_code)
    {
        $this->sender_address = new \FedExVendor\WPDesk\AbstractShipping\Shipment\Address();
        $this->sender_address->address_line1 = $address_line1;
        $this->sender_address->address_line2 = $address_line2;
        $this->sender_address->city = $city;
        $this->sender_address->postal_code = $postal_code;
        $this->sender_address->country_code = $country_code;
        $this->sender_address->state_code = $state_code;
    }
    /**
     * Get address.
     *
     * @return Address
     */
    public function get_address()
    {
        return $this->sender_address;
    }
}
