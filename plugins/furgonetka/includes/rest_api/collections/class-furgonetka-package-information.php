<?php

/**
 * Class Furgonetka_Package_Information_Collection
 */
class Furgonetka_Package_Information_Collection
{
    /**
     * @var object
     */
    private $collection;

    private $result = array();

    /**
     * @param object $collection
     *
     * @return $this
     */
    public function set_collection( object $collection )
    {
        $this->collection = $collection;
        return $this;
    }

    public function prepare_data_for_single_order_update()
    {
        $tracking_info_data = $this->convert_package_data( $this->collection->packages );

        $this->result[] = array(
            'key'   => 'tracking_info',
            'value' => $tracking_info_data,
        );

        return $this;
    }

    private function convert_package_data( array $data )
    {
        $result = array();
        foreach ( $data as $packgage ) {
            // tracking info
            $result[ $packgage->tracking->number ] = array(
                'courierService' => $packgage->tracking->courierService,
            );
        }
        return $result;
    }

    /**
     * @return array
     */
    public function get_results()
    {
         return $this->result;
    }
}
