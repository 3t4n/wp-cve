<?php
/**
 * Class Furgonetka_Update_Order - Managing connection to database and set metadata
 */
class Furgonetka_Update_Order
{
    private $order_id;
    private $data;

    /**
     * @param $order_id
     *
     * @return $this
     */
    public function set_orderId( $order_id )
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function set_data( array $data )
    {
        $this->data = $data;
        return $this;
    }

    /**
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
     * @return array
     */
    public function get_tracking_info()
    {
        $order = wc_get_order( $this->order_id );

        return $order->get_meta( 'tracking_info' );
    }
}
