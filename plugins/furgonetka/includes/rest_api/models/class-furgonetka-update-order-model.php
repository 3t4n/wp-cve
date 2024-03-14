<?php

/**
 * The file that defines model for return endpoint
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/models
 */

/**
 * Class Furgonetka_Update_Order_Model - Managing connection to database and set metadata
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/models
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Update_Order_Model
{
    /**
     * Order id
     *
     * @var mixed
     */
    private $order_id;

    /**
     * Order data
     *
     * @var array
     */
    private $data;

    /**
     * Set Order Id
     *
     * @param mixed $order_id - order id.
     *
     * @return $this
     */
    public function set_order_id( $order_id )
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * Set data
     *
     * @param array $data - data.
     *
     * @return $this
     */
    public function set_data( array $data )
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Save data to metadata
     *
     * @return $this
     */
    public function save_data_to_order_metadata()
    {
        $order = wc_get_order( $this->order_id );

        if ( $order ) {
            foreach ($this->data as $single_metadata) {
                $order->update_meta_data($single_metadata['key'], $single_metadata['value']);
            }

            $order->save();
        }

        return $this;
    }

    /**
     * Get tracking info
     *
     * @return array
     */
    public function get_tracking_info()
    {
        $order = wc_get_order( $this->order_id );

        return $order->get_meta( 'tracking_info' );
    }
}
