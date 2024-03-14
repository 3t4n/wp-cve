<?php

/**
 * UPS implementation: Rating implementation.
 *
 * @package WPDesk\UpsShippingService
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService;

use UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate;
/**
 * Can modify shipment rating.
 */
class UpsShipmentRatingImplementation implements \UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating
{
    /**
     * Rates.
     *
     * @var SingleRate[]
     */
    private $rates;
    /**
     * Is access point rating?
     *
     * @var bool
     */
    private $is_access_point_rating = \false;
    /**
     * UpsShipmentRatingImplementation constructor.
     *
     * @param array $rates .
     * @param bool  $is_access_point_rating .
     */
    public function __construct(array $rates, $is_access_point_rating = \false)
    {
        $this->rates = $rates;
        $this->is_access_point_rating = $is_access_point_rating;
    }
    /**
     * @param ShipmentRating $rating .
     */
    public function merge_ratings(\UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $rating)
    {
        foreach ($rating->get_ratings() as $rate) {
            $this->rates[] = $rate;
        }
    }
    /**
     * Get ratings.
     *
     * @return SingleRate[]
     */
    public function get_ratings()
    {
        if ($this->is_access_point_rating) {
            foreach ($this->rates as $rate) {
                if (!$rate->is_collection_point_rate) {
                    $rate->is_collection_point_rate = \true;
                    $rate->service_name .= ' ' . \__('(Access Point)', 'flexible-shipping-ups');
                }
            }
        }
        return $this->rates;
    }
}
