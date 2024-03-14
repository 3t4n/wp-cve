<?php

/**
 * The file that defines package information class
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/collections
 */

/**
 * Class Furgonetka_Package_Information_Collection - manage package information coleection
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/collection
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Package_Information_Collection
{
    /**
     * Collection
     *
     * @var object
     */
    private $collection;

    /**
     * Collection result
     *
     * @var array
     */
    private $result = array();

    /**
     * Set collection data
     *
     * @param object $collection - initial data.
     *
     * @return $this
     */
    public function set_collection( object $collection )
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * Prepare data for songle order update.
     *
     * @return $this
     */
    public function prepare_data_for_single_order_update()
    {
        $tracking_info_data = $this->convert_package_data( $this->collection->packages );

        $this->result[] = array(
            'key'   => 'tracking_info',
            'value' => $tracking_info_data,
        );

        return $this;
    }

    /**
     * Convert package data
     *
     * @param  array $data - package data.
     *
     * @return array
     */
    private function convert_package_data( array $data )
    {
        $result = array();
        foreach ( $data as $packgage ) {
            // Tracking info.
            $result[ $packgage->tracking->number ] = array(
                'courierService' => $packgage->tracking->courierService,
            );
        }
        return $result;
    }

    /**
     * Get prepared collection.
     *
     * @return array
     */
    public function get_results()
    {
        return $this->result;
    }
}
