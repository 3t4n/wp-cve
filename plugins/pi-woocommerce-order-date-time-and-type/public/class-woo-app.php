<?php

class pisol_dtt_woo_mobile_app_support{

    protected $post_type = 'shop_order';

    function __construct(){
        $add_to_order_note = pisol_dtt_get_setting('pi_enable_woocommerce_app_support', 0);

        if(empty($add_to_order_note)) return;

        add_action( 'woocommerce_checkout_update_order_meta', [$this,'addDataInNote'],  PHP_INT_MAX, 1  );

        add_action( 'save_post', array($this,'savePostTimeSlot'), PHP_INT_MAX, 2 );
    }

    function savePostTimeSlot( $post_id, $post ){
        
        if(!apply_filters( 'pisol_dtt_order_note_on_edit', true )) return;

        if ( ( empty( $post_id ) || empty( $post ) || ( $this->post_type !== get_post_type( $post ) ) )
          || ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id )
          || ( ! current_user_can( 'edit_post', $post_id ) ) ) {
              return;
          }


         $this->addDataInNote( $post_id );
    }

    function addDataInNote( $order_id ){
        $order = wc_get_order($order_id);

        $type = $order->get_meta( 'pi_delivery_type', true );   // delivery type 'delivery' or 'pickup'
        
        $date = $order->get_meta( 'pi_system_delivery_date', true ); // date in yyyy/mm/dd format
        if(class_exists('pi_dtt_date')){
            $date = pi_dtt_date::translatedDate($date);
        }

        $time = $order->get_meta( 'pi_delivery_time', true );  // delivery time
        if(class_exists("pisol_dtt_time")){
            $time = pisol_dtt_time::formatTimeForDisplay($time);
        }

        $location = $order->get_meta( 'pickup_location', true ); // pickup location
        
        $note = __('Delivery Type:', 'pisol-dtt').' '.$type;
        $note .= '<br>'.__('Data:','pisol-dtt').' '.$date;
        $note .= '<br>'.__('Time:','pisol-dtt').' '.$time;
        if(!empty($location)){
            $note .= '<br>'.__('Location:','pisol-dtt').' '.$location;
        }

        $note = apply_filters('pisol_dtt_order_note_filter', $note, $order);

        $order->add_order_note( $note );
    }

}
new pisol_dtt_woo_mobile_app_support();